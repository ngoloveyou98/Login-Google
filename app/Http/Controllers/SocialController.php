<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator,Redirect,Response,File;

use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;

class SocialController extends Controller
{

public function redirect()
{
    return Socialite::driver('google')->redirect();
}

public function callback()
{
    try {
            $user = Socialite::driver('google')->user();
            $finduser  = User::where('google_id', $user->id)->first();

            if($finduser ){
                Auth::login($finduser );
                return redirect('homepage');
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('123456dummy')
                ]);
                
                Auth::login($newUser);
                return redirect('homepage');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            // redirect('login');
        }
   }
}