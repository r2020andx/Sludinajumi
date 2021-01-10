<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use Gumlet\ImageResize;
use Illuminate\Support\Facades\Auth;

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
        $geoCodeDataLat = $geoCodeData->results[0]->geometry->lat;
        $geoCodeDataLng = $geoCodeData->results[0]->geometry->lng;
        // End geocoding
        $photosLocation = $ad->photosFolder;
        $photos = array_diff(scandir('./'.$photosLocation), array('..', '.', '_preview')); // Noņem Linux tipa punktus folderiem

        return view('show', 
            [
                'id'=> $id,
                'ad' => $ad,
                'geoLat' => $geoCodeDataLat,
                'getLng' => $geoCodeDataLng,
                'photosLocation' => $photosLocation,
                'photos' => $photos
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
        $ad->photosFolder = 'img/ads/' . uniqid();
        $ad->views = 0;
        $ad->rating = 0;
        
        mkdir($ad->photosFolder);               // Attēlu folderis
        mkdir($ad->photosFolder.'/_preview');   // Mazo attēlu folderis
        $photos = $_FILES['photos']['tmp_name'];
        foreach ($photos as $photo) {
                
                // Upload fullsize image
                move_uploaded_file($photo, realpath($ad->photosFolder).'\\'.basename($photo).'.jpg');
                // Upload preview image
                $smallPhoto = new ImageResize(realpath($ad->photosFolder).'\\'.basename($photo).'.jpg');
                $smallPhoto->resizeToHeight(200);
                $smallPhoto->save(realpath($ad->photosFolder).'\\_preview\\'.basename($photo).'.jpg');

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
        Ad::destroy($id);
        $message = "Sludinājums ir dzēsts";
        return redirect('/ads')->with(['message' => $message]);
    }
   
}