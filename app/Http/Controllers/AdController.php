<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Visits;
use Gumlet\ImageResize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AdController extends Controller
{   
    protected static $photosBasePathRoot = "./public/img/ads/";
    protected static $photosBasePathPublic = "./storage/img/ads/";
    protected static $photosPreviewPath = "/_preview/";
    protected static $photosResizedPath = "/_resized/";

    public static function getPhotosFileNames($id, $firstOneOnly = FALSE) {
        $ad = Ad::where('id', $id)->first();
        $photosFullPaths = Storage::files(self::$photosBasePathRoot . $ad->photosFolder);
        $photosFileNames = array_map('basename', $photosFullPaths);
        if ($firstOneOnly) {
            return $photosFileNames[0];
        } else {
            return $photosFileNames;
        }
    }
    public static function resizeAndUploadPhotos($photosFolder, $photoFile) {
        // Saglabā pilnā izmēra bildi
        $photoFilePath = Storage::putFile(self::$photosBasePathRoot . $photosFolder, $photoFile->getRealPath());
        $photoFileName = basename($photoFilePath);

        // Maina izmēru un saglabā
        $resizedPhoto = new ImageResize($photoFile->getRealPath());
        $resizedPhoto->gamma(false); // Neizmantot krāsu korekciju -> https://packagist.org/packages/gumlet/php-image-resize
         
        $resizedPhoto   // Optimāla izmēra bilde
                        ->resizeToWidth(1200, true)   // allow_enlarge = true
                        ->save(self::$photosBasePathPublic . $photosFolder . self::$photosResizedPath . $photoFileName)
                        // Samazināta izmēra bilde
                        ->resizeToWidth(400)
                        ->save(self::$photosBasePathPublic . $photosFolder . self::$photosPreviewPath . $photoFileName);
    }

    public function index() {
            $ads = Ad::all();
            $noAdsMessage = "";
            if ($ads->isEmpty()) {
                $noAdsMessage = "Nav neviena sludinājuma";
            }
            return view('index', ['ads' => $ads,
                                  'noAdsMessage' => $noAdsMessage,
                                  'photosBasePathPublic' => self::$photosBasePathPublic,
                                  'photosPreviewPath' => self::$photosPreviewPath
                                 ]);
    }
    public function indexMyAds() {
            $ads = Ad::where('owner', Auth::id())->get();
            $noAdsMessage = "";
            if ($ads->isEmpty()) {
                $noAdsMessage = "Nav neviena sludinājuma";
            }
        return view('index', ['ads' => $ads,
                              'noAdsMessage' => $noAdsMessage,
                              'photosBasePathPublic' => self::$photosBasePathPublic,
                              'photosPreviewPath' => self::$photosPreviewPath
                             ]);
    }

    public function show($id) {
        $ad = Ad::where('id', $id)->first();
        // Geocoding - konvertē ielas adresi uz lat. & lang.
        $geoCodeApiKey = "05537e2574534d6a9b1e66be98b638cc"; // OpenCage Geocoding API key
        $geoCodeApiURI = "https://api.opencagedata.com/geocode/v1/json";
        $geoCodeStreet = $ad->street;
        $geoCodeCity = $ad->city;
        $geoCodeAddress = urlencode("$geoCodeStreet $geoCodeCity");
        $geoCodeQuery = "$geoCodeApiURI?q=$geoCodeAddress&pretty=1&key=$geoCodeApiKey";
        $json = file_get_contents($geoCodeQuery);
        $geoCodeData = json_decode($json);
        
        if($geoCodeData->total_results == 0 || $geoCodeData->status->code != 200) {
            $addressIsValid = FALSE;
            $geoCodeDataLat = NULL;
            $geoCodeDataLng = NULL;
        } else {
            $addressIsValid = TRUE;
            $geoCodeDataLat = $geoCodeData->results[0]->geometry->lat;
            $geoCodeDataLng = $geoCodeData->results[0]->geometry->lng;
        }

        $clientIp = $_SERVER['REMOTE_ADDR'];
        $isVisited = Visits::where('ip_address', $clientIp)->where('ad_id', $ad->id)->first();   // Pirmais ieraksts DB, kur klienta IP ir apmeklējusi sludinājuma ID
        if ( !isset($isVisited) ) { // Ja nepastāv ieraksts, kur klienta IP ir apmeklējusi sludinājuma ID
                ++$ad->views;       // Pieskaita vienu skatījumu
                $ad->save();
        }
        // Saglabā datubāzē apmeklējuma IP un sludinājuma ID
        $visitEntry = new Visits();
        $visitEntry->ip_address = $clientIp;
        $visitEntry->ad_id = $ad->id;
        $visitEntry->save();

        return view('show', 
            [
                'id'=> $id,
                'ad' => $ad,
                'geoLat' => $geoCodeDataLat,
                'getLng' => $geoCodeDataLng,
                'photosPreviewPath' => self::$photosBasePathPublic . $ad->photosFolder . self::$photosPreviewPath,
                'photosResizedPath' => self::$photosBasePathPublic . $ad->photosFolder . self::$photosResizedPath,
                'photosFileNames' => $this->getPhotosFileNames($ad->id), 
                'addressIsValid' => $addressIsValid
            ]);
    }

    public function create() {
        return view('create');
    }

    public function store(Request $request) {

        $request->validate(['photos.*' => 'mimes:jpg,png']); // Vai ienākošie faili ir attēla formātā

        $ad = new Ad();
        $ad->make = $request->make;
        $ad->model = $request->model;
        $ad->year = $request->year;
        $ad->mileage = $request->mileage;
        $ad->price = $request->price;
        $ad->street = $request->street;
        $ad->city = $request->city;
        $ad->owner = Auth::id();        // Sludinājuma autors
        $ad->photosFolder = uniqid();
        $ad->views = 0;
        $ad->rating = 0;

        // Pilna izmēra attēlu folderis
        Storage::makeDirectory(self::$photosBasePathRoot . $ad->photosFolder);
        // Mazo attēlu folderis
        Storage::makeDirectory(self::$photosBasePathRoot . $ad->photosFolder . self::$photosPreviewPath);
        // Optimizētu attēlu folderis 
        Storage::makeDirectory(self::$photosBasePathRoot . $ad->photosFolder . self::$photosResizedPath); 

        foreach ($request->photos as $photo) {     
            self::resizeAndUploadPhotos($ad->photosFolder, $photo);           
        }
         
        $ad->save();
        $message = "Pievienots";
        return redirect('/'.$ad->id)->with(['message' => $message]);
    }

    public function edit($id) {
        $ad = Ad::where('id', $id)->first();
        return view('edit', ['id' => $id,
                             'ad' => $ad,
                             'photosPreviewPath' => self::$photosBasePathPublic . $ad->photosFolder . self::$photosPreviewPath,
                             'photosResizedPath' => self::$photosBasePathPublic . $ad->photosFolder . self::$photosResizedPath,
                             'photosFileNames' => $this->getPhotosFileNames($ad->id), 
                            ]);
    }

    public function update($id, Request $request) {
        $ad = Ad::where('id', $id)->first();

        if ( isset($request->newPhoto) ) {  // Ja dati nāk no foto nomaiņas formas
                // Izdzēš veco foto
                $photoFileToDelete = basename($request->currentPhoto); // Atrod faila nosaukumu no iesūtītās formas
                Storage::delete([
                                self::$photosBasePathRoot . $ad->photosFolder . "/" . $photoFileToDelete,
                                self::$photosBasePathRoot . $ad->photosFolder . self::$photosPreviewPath . $photoFileToDelete,
                                self::$photosBasePathRoot . $ad->photosFolder . self::$photosResizedPath . $photoFileToDelete
                                ]);

                // Ielādē jauno foto
                self::resizeAndUploadPhotos($ad->photosFolder, $request->newPhoto); 
                $message = "Attēls saglabāts";
                return redirect('/'.$id.'/edit')->with(['message' => $message]);

        } else {                            
                // Ja dati nāk no teksta formas
                $ad->make = $request->make;
                $ad->model = $request->model;
                $ad->year = $request->year;
                $ad->mileage = $request->mileage;
                $ad->price = $request->price;
                $ad->street = $request->street;
                $ad->city = $request->city;
                $ad->save();
                $message = "Saglabāts";
                return redirect('/'.$id)->with(['message' => $message]);
        }
    }

    public function delete($id) {
        $ad = Ad::where('id', $id)->first();    
        if ($ad->owner == Auth::id()) {               // Ja pieder sludinājums
            return view('delete', [ 'id' => $id,
                                    'ad' => $ad ]);   // Parādīt dzēšanas dialogu
        } else {
            $message = "Nav pieejas";
            return redirect('/'.$id)->with(['id' => $id, 'ad' => $ad, 'message' => $message]);
        }
    }

    public function destroy($id) {
        $ad = Ad::where('id', $id)->first();
        Ad::destroy($id);
        Storage::deleteDirectory(self::$photosBasePathRoot . $ad->photosFolder); // Dzēst attēlu folderi
        $message = "Sludinājums ir dzēsts";
        return redirect('/')->with(['message' => $message]);
    }
  
}