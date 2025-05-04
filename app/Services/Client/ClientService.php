<?php

namespace App\Services\Client;

use App\Models\Clients\Client;
use App\Enums\Client\AddableToBulk;
use App\Models\Clients\ClientEmail;
use App\Models\Clients\ClientPhone;
use App\Enums\Client\AddableToBulck;
use App\Filters\Client\FilterClient;
use App\Models\Clients\ClientAddress;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ClientService{

    public function allClients(){

        $clients = QueryBuilder::for(Client::class)
        ->allowedFilters(['name'
            // AllowedFilter::exact('clientId', 'id'), // Add a custom search filter
            // AllowedFilter::custom('search', new FilterClient()), // Add a custom search filter
        ])
        ->get();
        return $clients;

    }

    public function createClient(array $clientData){
        $client = Client::create([
            "name"=> $clientData["name"],
            "description"=> $clientData["description"]?? null,
        ]);
        $countAddresses = $clientData['addresses'] ? count($clientData['addresses']) : 0;
       if($countAddresses>0){
          foreach ($clientData['addresses'] as $address) {
            ClientAddress::create([
                "title"=>$address["address"],
                "client_id"=>$client->id
                ]);
          }
        }
        $count = $clientData['emails'] ? count($clientData['emails']) : 0;
        if($count>0){
            foreach ($clientData["emails"] as $email) {
            ClientEmail::create([
            "email"=>$email['email'],
            "client_id"=>$client->id
            ]);
           }
        }
        $countPhones = $clientData['phones'] ? count($clientData['phones']) : 0;
        if( $countPhones>0){
            foreach ($clientData["phones"] as $phone) {
                ClientPhone::create([
                    "phone"=>$phone['phone'],
                    "client_id"=>$client->id
                ]);
            }
        }
        return $client;

    }

    public function editClient(int $clientId){
            $client = Client::with(["emails" ,"phones" , "addresses"])->findOrFail($clientId);
            return $client;
    }

    public function updateClient(array $clientData){
        $client = Client::findOrFail($clientData['clientId']);
        if($client){
            $client->update([
                "name"=> $clientData["name"],
                "description"=> $clientData["description"],
            ]);
            $client->save();
        }
        // if (isset($clientData['addresses'])) {
        //     foreach ($clientData['addresses'] as $address) {
        //         $clientAddress = ClientAddress::find($address['clientAddressId']);
        //         if (!$clientAddress) {
        //             return response()->json([
        //                 'message' => __('messages.error.not_found')
        //             ], 404);
        //         }
        //         if(!$clientAddress){
        //             return response()->json([
        //                 'message' => __('messages.error.not_found')
        //             ], 404);
        //         }
        //         $clientAddress->update([
        //             "title"=>$address['title']
        //         ]);
        //         $clientAddress->save();
        //     }
        // }
        // if(isset($clientData['emails'])){
        //     foreach ($clientData['emails'] as $email) {
        //             $clientEmail = ClientEmail::findOrFail($email['clientEmailId']);
        //             if(!$clientEmail){
        //                 return response()->json([
        //                     'message' => __('messages.error.not_found')
        //                 ], 404);
        //             }
        //             $clientEmail->update([
        //                 "email"=>$email['email']
        //             ]);
        //             $clientEmail->save();
        //         }
        // }
        // if(isset($clientData['phones'])){
        //     foreach ($clientData['phones'] as $phone) {
        //         $clientPhone = ClientPhone::findOrFail($phone['clientPhoneId']);
        //         if(!$clientPhone){
        //             return response()->json([
        //                 'message' => __('messages.error.not_found')
        //             ], 404);
        //         }
        //         $clientPhone->update([
        //             "phone"=>$phone['phone']
        //         ]);
        //         $clientPhone->save();
        //     }
        // }

        return $client;
    }

    public function deleteClient(int $id){

            $client = Client::findOrFail($id);
            $client->delete();


    }

}
