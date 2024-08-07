<?php

namespace App\Repositories;

use App\Models\AuthorizedIp;


class IPRepository{


    /**
     * Get all authorized IP addresses.
     *
     * @return \Illuminate\Database\Eloquent\Collection All authorized IP addresses.
    */
    public function getAllIp(){
        return AuthorizedIp::all();
    }


    /**
     * Add a new IP address to the authorized list.
     *
     * @param string $ipAddress The IP address to be added.
     * @return void
    */
    public function addIp($ipAddress){
        AuthorizedIp::create(['ip_address' => $ipAddress]);
    }


    /**
     * Get an IP address by its value.
     *
     * @param string $ipAddress The IP address to be retrieved.
     * @return \App\Models\AuthorizedIp|null The authorized IP address model or null if not found.
    */
    public function getIp($ipAddress){
        return AuthorizedIp::where('ip_address', $ipAddress)->first();
    }


    /**
     * Get an IP address by its ID.
     *
     * @param int $id The ID of the IP address to be retrieved.
     * @return \App\Models\AuthorizedIp The authorized IP address model.
    */
    public function getId($id){
        return AuthorizedIp::findOrFail($id);
    }


    /**
     * Update an existing IP address.
     *
     * @param \App\Models\AuthorizedIp $ipSave The authorized IP address model to be updated.
     * @return void
    */
    public function updateIp($ipSave){
        $ipSave->save();
    }


    /**
     * Delete an IP address by its ID.
     *
     * @param \App\Models\AuthorizedIp $id The authorized IP address model to be deleted.
     * @return void
    */
    public function deleteIp($id){
        $id->delete();
    }

}

?>