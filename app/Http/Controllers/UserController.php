<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Auth;
use Session;

class UserController extends Controller
{
    public function getSignup(){
        return view('user.signup');
    }
    public function postSignup(Request $request){
        $rules = [
            'email' => 'email|required|unique:users',
            'password' => 'required|min:4'
        ];

        $this->validate($request, $rules);

        $user = new User([
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'type' => User::DEFAULT_TYPE,
        ]);
        $user->save();

        Auth::login($user);

        if(Session::has('oldURL')){
            $oldURL = Session::get('oldURL');
            return redirect()->to($oldURL);
            Session::forget('oldURL');

        }

        return redirect()->route('getprofile');
    }
    public function getSignin(){
        return view('user.signin');
    }
    public function postSignin(Request $request){
        $rules = [
            'email' => 'email|required',
            'password' => 'required|min:4',
            'g-recaptcha-response' => 'required|captcha',
        ];

        $this->validate($request, $rules);

        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]))
        {
            if(Session::has('oldURL')){
                $oldURL = Session::get('oldURL');
                return redirect()->to($oldURL);
                Session::forget('oldURL');

            }
            return redirect()->route('getprofile');
        }
        return redirect()->back()->withInput();

    }
    public function getProfile(){
        return view('user.profile');
    }

    public function logOut(){

        Auth::logout();
        return redirect()->route('getsignin');
    }
}
