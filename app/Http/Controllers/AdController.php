<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Visits;
use Gumlet\ImageResize;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\type;
use function PHPUnit\Framework\isEmpty;

class AdController extends Controller
{
    public function index() {
            $ads = Ad::all();
            $noAdsMessage = "";
            if ($ads->isEmpty()) {
                $noAdsMessage = "Nav neviena sludinājuma";
            }
            
            return view('index', ['ads' => $ads, 'noAdsMessage' => $noAdsMessage]);
    }
    public function indexMyAds() {
            $ads = Ad::where('owner', Auth::id())->get();
            $noAdsMessage = "";
            if ($ads->isEmpty()) {
                $noAdsMessage = "Nav neviena sludinājuma";
            }
        return view('index', ['ads' => $ads, 'noAdsMessage' => $noAdsMessage]);
    }

    public function show($id) {
        $ad = Ad::where('id', $id)->first();
        // Geocoding - convert address to lat. & lang.
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

        $photosFullPath = "/public/img/ads/" . $ad->photosFolder;
        $photosPreviewPath = "/storage/img/ads/" . $ad->photosFolder . "/_preview";
        $photosResizedPath = "/storage/img/ads/" . $ad->photosFolder . "/_resized";
        $photosArray = Storage::files($photosFullPath);

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
                'photosFullPath' => $photosFullPath,
                'photosArray' => $photosArray,
                'photosPreviewPath' => $photosPreviewPath,
                'photosResizedPath' => $photosResizedPath,
                'addressIsValid' => $addressIsValid
            ]);
    }

    public function create() {
        return view('create');
    }

    public function store(Request $request) {
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
        $photosPath = "public/img/ads/";
        $photosPathPub = "storage/img/ads/";
        $photosFullSizePath =    $photosPath . $ad->photosFolder;
        $photosPreviewPath = $photosPath . $ad->photosFolder . "/_preview/";
        $photosResizedPath = $photosPath . $ad->photosFolder . "/_resized/";
        $photosPreviewPathPub = $photosPathPub . $ad->photosFolder . "/_preview/";
        $photosResizedPathPub = $photosPathPub . $ad->photosFolder . "/_resized/";

        Storage::makeDirectory($photosFullSizePath);    // Oriģinālo attēlu folderis
        Storage::makeDirectory($photosPreviewPath); // Mazo attēlu folderis
        Storage::makeDirectory($photosResizedPath); // Optimizētu attēlu folderis

        foreach ($request->file('photos') as $photo) {
                
                // Upload fullsize image
                $photoFilePath = Storage::putFile($photosFullSizePath, $photo->getRealPath());
                $photoFileName = basename($photoFilePath);

                // Resize and upload
                $resizedPhoto = new ImageResize($photo->getRealPath());
                // Optimized photo
                $resizedPhoto->resizeToWidth(1200);
                $resizedPhoto->save($photosResizedPathPub . $photoFileName);

                // Small preview photo
                $resizedPhoto->resizeToWidth(200);
                $resizedPhoto->save($photosPreviewPathPub . $photoFileName);
            }
         
        $ad->save();
        $message = "Pievienots";
        return redirect('/ads/'.$ad->id)->with(['message' => $message]);
    }

    public function edit($id) {
        $ad = Ad::where('id', $id)->first();
        return view('edit', ['id' => $id, 'ad' => $ad]);
    }

    public function update($id, Request $request) {
        $ad = Ad::where('id', $id)->first();
        $ad->make = $request->make;
        $ad->model = $request->model;
        $ad->year = $request->year;
        $ad->mileage = $request->mileage;
        $ad->price = $request->price;
        $ad->street = $request->street;
        $ad->city = $request->city;
        $ad->save();
        $message = "Saglabāts";
        return redirect('/ads/'.$id)->with(['message' => $message]);
    }

    public function delete($id) {
        $ad = Ad::where('id', $id)->first();    
        if ($ad->owner == Auth::id()) {             // Ja pieder sludinājums
            return view('delete', [ 'id' => $id,
                                    'ad' => $ad ]);   // Parādīt dzēšanas dialogu
        } else {
            $message = "Nav pieejas";
            return redirect('/ads/'.$id)->with(['id' => $id, 'ad' => $ad, 'message' => $message]);           // Citādi atgriezties
        }
    }

    public function destroy($id) {
        $ad = Ad::where('id', $id)->first();
        //Ad::destroy($id);
        //$result = Storage::deleteDirectory("img/ads/" . $ad->photosFolder);
        //dd($result);
        $message = "Sludinājums ir dzēsts";
        return redirect('/ads')->with(['message' => $message]);
    }

    
   
}