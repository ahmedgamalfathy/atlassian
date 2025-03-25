<?php

namespace App\Http\Controllers\Api\Dashboard\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordSendCode;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\User\UserResource;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends Controller
{
    public function sendCodeEmail(Request $request)
    {
        DB::beginTransaction();
        try{
            $user =User::where("email", $request->email)->first();
            $user->update([
                "code"=>rand(1000,9999),
                'expired_at' => now()->addMinutes(5),
            ]);
            Mail::to($request->email)->send(new ForgotPasswordSendCode($user, $user->code));
            DB::commit();
            return response()->json([
                "data"=>new UserResource($user),
            ]);
            }catch(ValidationException $e){
                DB::rollBack();
                return response()->json([
                    "massage"=>$e->getMessage()
                ],422);
            }

    }
    public function verifyCodeEmail(Request $request)
    {
        DB::beginTransaction();
        try {
            $data= $request->validate([
                'code' => 'required|exists:users,code',
                'userId'=>['required','exists:users,id']
            ]);
           $user = User::findOrFail($data['userId']);
           if($user->code != $data['code']){
             return response()->json([
                'message'=>__('messages.error.not_found')
             ],422);
           }
           if($user->expired_at < now()){
            return response()->json([
                "message"=>'Time of code is expired ,please resend code again!',
            ],422);
            }
        //    $user->update([
        //         'code'=>null,
        //         'expired_at'=>null
        //     ]);
            DB::commit();
            return response()->json([
                'message'=>__('messages.success.created')
            ]);
        } catch (\Throwable $th) {
           DB::rollBack();
            return $th;
        }

    }
    public function resendCode(Request $request)
    {
        try{
            $user=User::findOrFail($request->userId);
            if($user){
                $code=rand(1000,9999);
                $user->update([
                'code'=>$code,
                'expired_at'=>now()->addMinutes(5)
                ]);
                Mail::to($user->email)->send(new ForgotPasswordSendCode($user, $user->code));
                return response()->json([
                    'message'=>__('messages.success.created')
                ],200);
            }else{
                return response()->json([
                   "message"=> __("messages.error.not_found")
                ],404);
            }

            }catch(\Exception $ex){
                return response()->json([
                  "message"=>'internal server error',
                ],500);
            }
    }
    public function newPassword(Request $request){
        DB::beginTransaction();
        try{
        $data=$request->validate([
            'userId'=>['required','exists:users,id'],
            'password'=>['required','confirmed',Password::min(8)]
        ]);
        $user=User::findOrFail($request->userId);
        if(!$user){
            return response()->json([
                'message'=>__('messages.error.not_found')
            ]);
        }
        if($user->expired_at < now()){
            return response()->json([
                "message"=>'Time of code is expired ,please resend code again!',
            ],422);
            }
        $user->update([
            'password'=>Hash::make($request->input('password')),
            'code'=>null,
            'expired_at'=>null
        ]);
        DB::commit();
            return response()->json([
                'data'=> new UserResource($user)
            ]);
        }catch(\Exception $ex){
            DB::rollBack();
        return response()->json([
            "message"=>'internal server error',
        ],500);
        }
    }

}
