<?php

namespace App\Http\Controllers\Api\Dashboard\Phone;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Clients\ClientPhone;
use App\Http\Controllers\Controller;

class PhoneController extends Controller
{
    public function __construct()
    {
        //all_phone,create_phone,edit_phone,update_phone,delete_phone
        $this->middleware('auth:api');
        $this->middleware('permission:all_phone', ['only' => ['index']]);
        $this->middleware('permission:create_phone', ['only' => ['create']]);
        $this->middleware('permission:edit_phone', ['only' => ['edit']]);
        $this->middleware('permission:update_phone', ['only' => ['update']]);
        $this->middleware('permission:delete_phone', ['only' => ['delete']]);
    }
    public function index(){
      $client = ClientPhone::all();
      return response()->json(["data"=>$client]);
    }
    public function edit(Request $request){
        $client = ClientPhone::find($request->clientPhoneId);
        if(!$client){
            return response()->json(["message"=> __("messages.error.not_found")],404);
        }
        return response()->json($client);
    }
    public function create(Request $request){
        $data = $request->validate([
            "clientId" => "required|exists:clients,id",
            "phone" => "required|numeric|unique:phones,phone",
        ]);
        $phone = new ClientPhone();
        $phone->client_id = $data["clientId"];
        $phone->phone = $data["phone"];
        $phone->save();
        return response()->json([
            "message"=>__("messages.success.created"),
        ]);
    }
    public function update(Request $request){
        $data = $request->validate([
            "clientId" => "required|exists:clients,id",
            "clientPhoneId" => "required|exists:phones,id",
            "phone" => ["required","numeric", Rule::unique('phones','phone')->ignore($request->clientPhoneId)],
        ]);
        $phone = ClientPhone::find($data["clientPhoneId"]);
        $phone->client_id = $data["clientId"];
        $phone->phone = $data["phone"];
        $phone->save();
        return response()->json([
            "message"=>__("messages.success.updated"),
        ]);
    }
    public function delete(Request $request){
        $phone = ClientPhone::find($request->clientPhoneId);
        $phone->delete();
        return response()->json([
            "message"=>__("messages.success.deleted"),
        ]);
    }
}
