<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

use App\Repositories\IPRepository;


class IPService{


    protected $iPRepository;


    /**
     * IPService constructor.
     *
     * @param IPRepository $iPRepository The IP repository instance.
    */
    public function __construct(IPRepository $iPRepository){
        $this->iPRepository = $iPRepository;
        Log::info('IPRepository initialized');
    }


    /**
     * Retrieve all IP addresses.
     *
     * @return Collection of all IP addresses.
    */
    public function getAllIp(){

        Log::info('Retrieving all IP addresses');

        try {
            
            // Retrieve all IP addresses using the repository.
            $ipAddresses = $this->iPRepository->getAllIp();

            Log::info('Successfully retrieved all IP addresses', ['ip_count' => $ipAddresses->count()]);

            return $ipAddresses;
        }catch (\Exception $e) {
         
            Log::error('Failed to retrieve IP addresses', ['error' => $e->getMessage()]);

            // Optionally, you could rethrow the exception or return an empty collection.
            throw new \Exception('Failed to retrieve IP addresses: ' . $e->getMessage());
        }

    }


    /**
     * Add a new IP address.
     *
     * @param string $ipAddress The IP address to be added.
     * @throws \Exception If the IP address already exists.
    */
    public function addIp($ipAddress){

        Log::info('Adding new IP address', ['ip_address' => $ipAddress]);

        // Check if the IP address already exists.
        $existingIp = $this->iPRepository->getIp($ipAddress);

        if ($existingIp) {
            Log::error('IP address already exists', ['ip_address' => $ipAddress]);
            throw new \Exception('IP Address already exists.');
        }

        // Add the new IP address using the repository.
        $this->iPRepository->addIp($ipAddress);

        Log::info('IP address added successfully', ['ip_address' => $ipAddress]);
    }


    /**
     * Update an existing IP address.
     *
     * @param int $id The ID of the IP address to be updated.
     * @param string $ipAddress The new IP address.
     * @throws \Exception If the IP address is not found or already exists.
    */
    public function updateIp($id, $ipAddress){

        Log::info('Updating IP address', ['id' => $id, 'new_ip_address' => $ipAddress]);

        // Retrieve the IP address by ID.
        $newIp = $this->iPRepository->getId($id);

        // Check if the IP address with the given ID exists.
        if(!$newIp){
            Log::error('IP address not found', ['id' => $id]);
            throw new \Exception('IP Address not found.');
        }

        // Check if the new IP address already exists.
        $existingIp = $this->iPRepository->getIp($ipAddress);

        // If the IP address already exists and is not the current one, throw an exception.
        if ($existingIp && $existingIp->id != $newIp->id) {
            Log::error('IP address already exists', ['existing_ip_id' => $existingIp->id, 'new_ip_address' => $ipAddress]);
            throw new \Exception('IP Address already exists.');
        }

        // Update the IP address with the new value.
        $newIp->ip_address = $ipAddress;

        // Save the updated IP address using the repository.
        $this->iPRepository->updateIp($newIp);       
        
        Log::info('IP address updated successfully', ['id' => $id, 'new_ip_address' => $ipAddress]);
    }


    /**
     * Delete an IP address.
     *
     * @param int $id The ID of the IP address to be deleted.
     * @throws \Exception If the IP address is not found.
    */
    public function deleteIp($id){

        Log::info('Deleting IP address', ['id' => $id]);

        // Retrieve the IP address by ID.
        $id = $this->iPRepository->getId($id);

        // Check if the IP address with the given ID exists.
        if(!$id){
            Log::error('IP address not found', ['id' => $id]);
            throw new \Exception('IP Address not found.');
        }

        // Delete the IP address using the repository.
        $this->iPRepository->deleteIp($id);

        Log::info('IP address deleted successfully', ['id' => $id]);
    }



    /**
     * Validate if an IP address is authorized.
     *
     * @param string $ip The IP address to be validated.
     * @return bool True if the IP address is authorized, false otherwise.
    */
    public function validateIp($ip){

        Log::info('Validating IP address', ['ip' => $ip]);

        $authorizedIps = $this->iPRepository->getAllIp();

        foreach ($authorizedIps as $authorizedIp){

            if ($authorizedIp->ip_address === $ip){
                Log::info('IP address is authorized', ['ip' => $ip]);
                return true;
            }

            if ($this->isIpInCidr($ip, $authorizedIp->ip_address)){
                Log::info('IP address is authorized in CIDR range', ['ip' => $ip, 'cidr' => $authorizedIp->ip_address]);
                return true;
            }
        }

        Log::warning('IP address is not authorized', ['ip' => $ip]);

        return false;
    }



    /**
     * Check if an IP address is within a CIDR range.
     *
     * @param string $ipAddress The IP address to be checked.
     * @param string $cidr The CIDR range.
     * @return bool True if the IP address is within the CIDR range, false otherwise.
    */
    public function isIpInCidr($ipAddress, $cidr){

        Log::info('Checking if IP address is within CIDR range', ['ip' => $ipAddress, 'cidr' => $cidr]);

        // Ensure the CIDR notation is valid.
        if (strpos($cidr, '/') === false){
            Log::warning('Invalid CIDR notation', ['cidr' => $cidr]);
            return false;
        }

        // Split the subnet and mask.
        list($subnet, $mask) = explode('/', $cidr);
        $mask = intval($mask);

        // Convert IP addresses to long format.
        $ip = ip2long($ipAddress);
        $subnet = ip2long($subnet);

        // Calculate the wildcard mask.
        $wildcard = ~((1 << (32 - $mask)) - 1);

        // Check if the IP address is within the subnet range.
        $isInCidr = ($ip & $wildcard) == ($subnet & $wildcard);

        if ($isInCidr){
            Log::info('IP address is within CIDR range', ['ip' => $ipAddress, 'cidr' => $cidr]);
        } else{
            Log::warning('IP address is not within CIDR range', ['ip' => $ipAddress, 'cidr' => $cidr]);
        }

        return $isInCidr;
    }

}

?>