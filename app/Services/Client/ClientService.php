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


        if(isset($clientData['addresses']) && is_array($clientData['addresses'])) {
            foreach ($clientData['addresses'] as $address) {
                if(isset($address['address'])) {
                    ClientAddress::create([
                        "title"=>$address["address"],
                        "client_id"=>$client->id
                    ]);
                }
            }
        }

        // Handle emails if they exis
        if(isset($clientData['emails']) && is_array($clientData['emails'])) {
            foreach ($clientData["emails"] as $email) {
                if(isset($email['email'])) {
                    ClientEmail::create([
                        "email"=>$email['email'],
                        "client_id"=>$client->id
                    ]);
                }
            }
        }

        // Handle phones if they exist
        if(isset($clientData['phones']) && is_array($clientData['phones'])) {
            foreach ($clientData["phones"] as $phone) {
                if(isset($phone['phone'])) {
                    ClientPhone::create([
                        "phone"=>$phone['phone'],
                        "client_id"=>$client->id
                    ]);
                }
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
