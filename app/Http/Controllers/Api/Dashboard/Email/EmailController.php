<?php

namespace App\Http\Controllers\Api\Dashboard\Email;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Clients\ClientEmail;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    public function __construct()
    {
        //all_email,create_email,edit_email,update_email,delete_email
        $this->middleware('auth:api');
        $this->middleware('permission:all_email', ['only' => ['index']]);
        $this->middleware('permission:create_email', ['only' => ['create']]);
        $this->middleware('permission:edit_email', ['only' => ['edit']]);
        $this->middleware('permission:update_email', ['only' => ['update']]);
        $this->middleware('permission:delete_email', ['only' => ['delete']]);
    }
 public function index(Request $request)
 {
     $data = ClientEmail::where('client_id',$request->clientId)->get();
     return response()->json(["data"=>$data]);
 }
  public function create(Request $request)
    {
        $data = $request->validate([
            "clientId" => "required|exists:clients,id",
            "email" => "required|email|unique:emails,email",
        ]);
        $email = new ClientEmail();
        $email->client_id = $data["clientId"];
        $email->email = $data["email"];
        $email->save();
        return response()->json([
            "message"=>__("messages.success.created"),
        ]);
    }
  public function edit(Request $request)
    {
        $email = ClientEmail::find($request->clientEmailId);
        if(!$email) {
            return response()->json(["message"=> __("messages.error.not_found")]);
        }
        return response()->json(["data"=>$email]);
    }
  public function update(Request $request)
  {
    $data = $request->validate([
        "clientId" => "required|exists:clients,id",
        "clientEmailId"=>"required|exists:emails,id",
        "email" => [
            "required",
            "email",
            Rule::unique('emails', 'email')->ignore($request->clientEmailId)
        ],

    ]);
    $email = ClientEmail::find($data['clientEmailId']);
    $email->client_id = $data['clientId'];
    $email->email = $data['email'];
    $email->save();
    return response()->json(["message"=> __("messages.success.updated")]);
  }
  public function delete(Request $request)
  {
    $email = ClientEmail::find($request->clientEmailId);
    if(!$email){
       return response()->json(["message"=> __("messages.error.not_found")]);
    }
    $email->delete();
    return response()->json(["message"=> __("messages.success.deleted")]);
  }
}
