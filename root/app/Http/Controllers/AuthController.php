<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Routing\Controller;
use App\User;
use Validator;
use Auth;
use Mail;
use Hash;

class AuthController extends Controller
{
    public function authenticate(Request $request){
      $validator = Validator::make($request->all(), ['email'=>'required|email','password'=>'required']);
      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
      $user = User::where('email', $request->email)->first();
      if ($user) {
        if (Hash::check($request['password'],$user->password)) {
          if ($user->active == 1) {
            Auth::login($user);
            return redirect('/home');
          }
            return redirect('/activeCode/'.$user->name);
        }
      }
      $error['message']="invalid E-mail or password";
      return redirect('/login')->withErrors($error)->withInput();
    }


    public function create(Request $request)
    {
       $status =  Validator::make($request->all(), [
           'name' => 'required|max:255|unique:users',
           'email' => 'required|email|max:255|unique:users',
           'password'=>'required|alpha_num|between:6,12|confirmed',
           'password_confirmation'=>'required|alpha_num|between:6,12',
           'profile_pic' => 'image|mimes:jpeg,bmp,png'
       ]);
      if ($status->fails()) {
        return redirect()->back()->withErrors($status)->withInput();
      }
      if (!empty($request->profile_pic)) {
        $destinationPath = 'image/'; // upload path
        $name = $request['profile_pic']->getClientOriginalName();
        $fileName = date("Y_h:i:s_A").'_'.$name; // renameing image
        $request['profile_pic']->move($destinationPath, $fileName);
      }
      else {
        $fileName = "default.png";
      }
      $activeCode = rand();
      $user = new User;
      $user->name = $request['name'];
      $user->email = $request['email'];
      $user->password = bcrypt($request['password']);
      $user->profile_pic = 'image/'.$fileName;
      $user->activeCode = $activeCode;
      $user->save();

      $name = $request['name'];
      $email = $request['email'];
      Mail::send('email',['activeCode' => $activeCode] , function ($message) use ($email,$name ) {
          $message->to($email, $name)->subject(' Laravel activation code');
      });
      return redirect('/activeCode/'.$name);
  }


   public function activate(Request $request){
     $validator = Validator::make($request->all(), ['activeCode'=>'required']);
     if ($validator->fails()) {
       return redirect('/activeCode/'.$request->name)->withErrors($validator)->withInput();
     }
     $user = User::where('name',$request->name)->first();
     if ($request->activeCode == $user->activeCode ) {
       $user->active = 1 ;
       $user->save();
       Auth::login($user);
       return redirect('/home');
     }
     $error['message']="invalid activation code";
     return redirect('/activeCode/'.$request->name)->withErrors($error)->withInput();
   }

   public function resendCode($name){
     $user = User::where('name',$name)->firstOrFail();
     if ($user) {
       $activeCode = rand();
       $name = $user->name;
       $email = $user->email;
       $user->activeCode = $activeCode;
       $user->save();
       Mail::send('email',['activeCode' => $activeCode] , function ($message) use ($email,$name ) {
           $message->to($email, $name)->subject('Laravel active code');
       });
       return redirect('/activeCode/'.$name);
     }
   abort(404);
   }


   public function logout(){
     Auth::logout();
     return redirect('/login');
   }
}
