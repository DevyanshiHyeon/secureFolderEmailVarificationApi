<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\VerifyEmail;
use Mail;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    function sendVerifyOtp(Request $request){
        $rules = [
            "email" => "required|email",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $user = User::where("email", $request->email)->first();
        $otp = rand(1000, 9999);
        $details = [
            "title" => "Verify Email",
            "otp" => $otp,
        ];
        if ($user) {
            $user->update(["otp" => $otp]);
            $email = Mail::to($user->email)->send(new VerifyEmail($details));
            $responce = [
              "success" => true,
              "failure" => false,
              "message" => "OTP Send Successfully."];
            return response()->json($responce, 200);
        } else {
            $createUser = User::create([
                "email" => $request->email,
                "otp" => $otp,
            ]);
            Mail::to($request->email)->send(new VerifyEmail($details));
            $responce = [
              "success" => true,
              "failure" => false,
              "message" => "OTP Send Successfully."];
            return response()->json($responce, 200);
        }
    }
    function verifyOtp(Request $request){
       $rules = [
              "email" => "required|email",
              "otp" => "required|max:4|min:4"
       ];
       $validator = Validator::make($request->all(), $rules);
       if ($validator->fails()) {
           return $validator->errors();
       }
       $user = User::where("email", $request->email)->first();
       if ($user) {
              if ($user->otp == $request->otp) {
                     $responce = [
                            "success" => true,
                            "failure" => false,
                            "message" => "Otp Varify Successfully"
                     ];
                     return response()->json($responce , 200);
              }else{
                     $responce = [
                            "success" => false,
                            "failure" => true,
                            "message" => "Otp is Not Match."
                     ];
                     return response()->json($responce , 200);
              }
       }else{
              $responce = [
                     "success" => false,
                     "failure" => true,
                     "message" => "User Not Found With This Email"];
              return response()->json($responce , 200);
       }
    }
    function updateEmail(Request $request){
       $rules = [
              "old_email" => "required|email",
              "new_email" => "required|email",
              "otp" => "required|max:4|min:4"
       ];
       $validator = Validator::make($request->all(), $rules);
       if ($validator->fails()) {
           return $validator->errors();
       }
       $user = User::where("email", $request->old_email)->first();
       if($user){
              if($user->otp == $request->otp){
              $user->update([
                     'email' => $request->new_email
              ]);
              $responce = [
                     "success" => true,
                     "failure" => false,
                     "message" => "Email Change Successfully.."];
              return response()->json($responce, 200);
       }else{
              $responce = [
                     "success" => false,
                     "failure" => true,
                     "message" => "OTP not Matched."];
              return response()->json($responce, 200);
       }
       }else{
              $responce = [
                     "success" => false,
                     "failure" => true,
                     "message" => "User Not Found."];
              return response()->json($responce, 200);
       }
    }
    function passwordChange(Request $request){
       $rules = [
              "email" => "required|email",
       ];
       $validator = Validator::make($request->all(), $rules);
       if ($validator->fails()) {
           return $validator->errors();
       }
       $user = User::where("email", $request->email)->first();
       $otp = rand(1000, 9999);
        $details = [
            "title" => "Verify Email",
            "otp" => $otp,
        ];
       if($user){
              $user->update([
                     'otp' => $otp
              ]);
              Mail::to($request->email)->send(new VerifyEmail($details));
       }else{
              $responce = [
                     "success" => false,
                     "failure" => true,
                     "message" => "User Not Found With This Email"];
              return response()->json($responce , 200);
       }
    }
}
