<?php
namespace App\Services\Select;
use App\Models\Clients\Client;
use App\Models\Clients\ClientEmail;
use App\Models\Clients\ClientPhone;

class ClientSelectService{
    public function getClients(){
        $clients = Client::all(['id as value', 'name as label']);
        return $clients;
    }
    public function getClientEmails($clientId){
            $clientEmails = ClientEmail::where('client_id', $clientId)->get(['id as value', 'email as label']);
        return $clientEmails;
    }
    public function getClientPhones($clientId){
        $clientPhones = ClientPhone::where('client_id', $clientId)->get(['id as value', 'phone as label']);
    return $clientPhones;
}
}
?>
