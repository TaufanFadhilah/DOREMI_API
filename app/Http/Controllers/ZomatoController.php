<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ZomatoController extends Controller
{
    public $token = "02868becba07c6991b590d7336fe3ab8";

    public function getCities(){
      $cities = [
        [
          "id" => 11052,
          "city" => "Bandung"
        ],
        [
          "id" => 74,
          "city" => "Jakarta"
        ]
      ];

      return response()->json(['data' => $cities]);
    }

    public function getRestaurantByCity(Request $request){
      $client = new \GuzzleHttp\Client();
      $request = $client->get('https://developers.zomato.com/api/v2.1/search?entity_id='.$request->city_id.'&entity_type=city',[
        'headers' => [
          'Accept' => 'application/json',
          'user-key' => $this->token
        ]
      ]);
      $response = $request->getBody()->getContents();

      return response()->json(['data' => json_decode($response)]);
    }

    public function getRestaurantByQuery(Request $request){
      $url = 'https://developers.zomato.com/api/v2.1/search?q='.$request->keyword;
      $client = new \GuzzleHttp\Client();
      $request = $client->get($url,[
        'headers' => [
          'Accept' => 'application/json',
          'user-key' => $this->token
        ]
      ]);
      $response = $request->getBody()->getContents();

      return response()->json(['data' => json_decode($response)]);
    }

    public function getDetailRestaurant(Request $request){
      $url = 'https://developers.zomato.com/api/v2.1/restaurant?res_id='.$request->res_id;
      $client = new \GuzzleHttp\Client();
      $request = $client->get($url,[
        'headers' => [
          'Accept' => 'application/json',
          'user-key' => $this->token
        ]
      ]);
      $response = $request->getBody()->getContents();

      return response()->json(['data' => json_decode($response)]);
    }

    public function getReviewRestaurant(Request $request){
      $url = 'https://developers.zomato.com/api/v2.1/reviews?res_id='.$request->res_id.'&count=10';
      $client = new \GuzzleHttp\Client();
      $request = $client->get($url,[
        'headers' => [
          'Accept' => 'application/json',
          'user-key' => $this->token
        ]
      ]);
      $response = $request->getBody()->getContents();

      return response()->json(['data' => json_decode($response)]);
    }

}
