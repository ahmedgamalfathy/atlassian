<?php

namespace App\Http\Controllers\Api\Dashboard\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clients\ClientEmail;
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
     $data = ClientEmail::all();
     return response()->json(["data"=>$data]);
 }
  public function create(Request $request)
    {
        $data = $request->validate([
            "clientId" => "required|exists:clients,id",
            "email" => "required|email",
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
        "email" => "required|email",
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
