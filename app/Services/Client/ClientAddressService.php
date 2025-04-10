<?php
 namespace App\Services\Client;



use App\Models\clients\ClientAddress;
use Illuminate\Support\Facades\Request;

    class ClientAddressService
    {
        public function allClientAddress( $clientId)
        {
            return ClientAddress::where('client_id',$clientId)->get();
        }
        public function editClientAddress(int $id)
        {
            return ClientAddress::find($id);
        }
        public function createClientAddress(array $data)
        {
            return ClientAddress::create([
                'client_id' => $data['clientId'],
                'title' => $data['title'],

            ]);
        }
        public function updateClientAddress(array $data)
        {
            $clientAddress = ClientAddress::find($data['clientAddressId']);
            $clientAddress->update([
                'client_id' => $data['clientId'],
                'title' => $data['title'],
            ]);
            return $clientAddress;
        }
        public function deleteClientAddress(int $id)
        {
            $clientAddress = ClientAddress::find($id);
            $clientAddress->delete();
        }
    }
