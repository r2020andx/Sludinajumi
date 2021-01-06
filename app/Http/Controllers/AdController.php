<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use Illuminate\Contracts\Session\Session;
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
        $ad = Ad::where('id', $id)->get();
        return view('show', ['id'=> $id, 'ad' => $ad]);
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
        $ad->views = 0;
        $ad->rating = 0;
        $ad->save();
        $message = "Pievienots";
        return redirect('/ads')->with(['message' => $message]);
    }

    public function edit($id) {
        $ad = Ad::where('id', $id)->get();
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
            return view('delete', ['id' => $id]);   // Parādīt dzēšanas dialogu
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
