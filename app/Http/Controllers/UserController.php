<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Transaction;
use Validator;
use DB;
use Storage;

class UserController extends Controller
{

    public $successStatus = 200;
    private $client;

    public function __construct(){
        $this->client = DB::table('oauth_clients')->where('id', 1)->first();
    }

    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('nApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'avatar' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $avatar = Storage::disk('public')->put('avatars', $request->avatar);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['avatar'] = $avatar;
        $user = User::create($input);

        $client = \Laravel\Passport\Client::where('password_client', 1)->first();

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => $request['email'],
            'password'      => $request['password'],
            'scope'         => null,
        ]);

        // Fire off the internal request.
        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return \Route::dispatch($proxy);
    }

    public function detail(){
        $user = Auth::user();
        return response()->json(['data' => $user], $this->successStatus);
    }

    public function getBalance(){
      return response()->json(['data' => [
        'balance' => Auth::user()->balance
        ]]);
    }

    public function addBalance(Request $request){
      $user = User::find(Auth::user()->id);
      $user->balance += $request->amount;
      $user->save();

      Transaction::create([
        'user_id' => Auth::user()->id,
        'amount' => $request->amount,
        'status' => 'debit'
      ]);

      return response()->json(['data' => [
        'status' => 'success'
        ]]);
    }

    public function buyFood(Request $request){
      Transaction::create([
        'user_id' => Auth::user()->id,
        'restaurant_id' => $request->res_id,
        'amount' => $request->amount,
        'status' => 'credit'
      ]);
      $user = User::find(Auth::user()->id);
      $user->balance -= $request->amount;
      $user->save();
      return response()->json(['data' => [
        'status' => 'success'
        ]]);
    }

    public function getHistory(){
      $transactions = Transaction::where('user_id', Auth::user()->id)->get();
      return response()->json(['data' => $transactions]);
    }
}
