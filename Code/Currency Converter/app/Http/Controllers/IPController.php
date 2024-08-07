<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

use App\Http\Requests\IPController\IPRequest;
use App\Services\IPService;



class IPController{


    private $iPService;

    

    /**
     * IPController constructor.
     *
     * @param IPService $iPService The IP service instance.
    */
    public function __construct(IPService $iPService){
        $this->iPService = $iPService;
        Log::info('IPService initialized');
    }



    /**
     * Manage IP addresses.
     *
     * @return The view with the IP addresses data.
    */
    public function ipManagement(){

        Log::info('Starting IP management');

        try {
            // Retrieve all IP addresses using the IP service.
            $ipAddresses = $this->iPService->getAllIp();

            Log::info('Retrieved IP addresses', ['ip_addresses' => $ipAddresses]);


            // Return the view with the users and role
            return view('ip_management')->with([
                'ipAddresses' => $ipAddresses
            ]);
        }catch (\Exception $e) {
            Log::error('Failed to manage IP addresses', ['error' => $e->getMessage()]);
            session()->flash('error',$e->getMessage());
        }
    }



    /**
     * Add a new IP address.
     *
     * @param IPRequest $request The incoming request containing the IP address.
     * @return RedirectResponse Redirect to the IP management route.
    */
    public function ipAddressAdd(IPRequest $request){
        
        // Retrieve the IP address from the request input.
        $ipAddress = $request->input('ip_address');

        Log::info('Adding new IP address', ['ip_address' => $ipAddress]);

        try {
            // Attempt to add the IP address using the IP service.
            $this->iPService->addIp($ipAddress);

            Log::info('IP address added successfully', ['ip_address' => $ipAddress]);

            session()->flash('success', 'IP Address added successfully.');
        } catch (\Exception $e){
            Log::error('Failed to add IP address', ['ip_address' => $ipAddress, 'error' => $e->getMessage()]);
            session()->flash('error',$e->getMessage());
        }
        
        // Redirect to the IP management route.    
        return redirect()->route('ip.management');
    }


    
    /**
     * Update an existing IP address.
     *
     * @param IPRequest $request The incoming request containing the IP address details.
     * @return RedirectResponse Redirect to the IP management route.
    */
    public function ipAddressUpdate(IPRequest $request){

        // Retrieve the ID and IP address from the request input.
        $id = $request->input('id');
        $ipAddress = $request->input('ip_address');

        Log::info('Updating IP address', ['id' => $id, 'ip_address' => $ipAddress]);

        try {

            // Attempt to update the IP address using the IP service.
            $this->iPService->updateIp($id, $ipAddress);

            Log::info('IP address updated successfully', ['id' => $id, 'ip_address' => $ipAddress]);

            session()->flash('success', 'IP Address updated successfully.');
        } catch (\Exception $e){
            Log::error('Failed to update IP address', ['id' => $id, 'ip_address' => $ipAddress, 'error' => $e->getMessage()]);
            session()->flash('error',$e->getMessage());
        }
        
        // Redirect to the IP management route.
        return redirect()->route('ip.management');
    }



    /**
     * Delete an existing IP address.
     *
     * @param Request $request The incoming request containing the IP address ID.
     * @return RedirectResponse Redirect to the IP management route.
    */
    public function ipAddressDelete(Request $request){
        
        // Retrieve the ID from the request input.
        $id = $request->input('id');

        Log::info('Deleting IP address', ['id' => $id]);


        try {

            // Attempt to delete the IP address using the IP service.
            $this->iPService->deleteIp($id);

            Log::info('IP address deleted successfully', ['id' => $id]);

            session()->flash('success', 'IP Address deleted successfully.');
        } catch (\Exception $e){
            Log::error('Failed to delete IP address', ['id' => $id, 'error' => $e->getMessage()]);
            session()->flash('error',$e->getMessage());
        }    
        
        // Redirect to the IP management route.
        return redirect()->route('ip.management');
    }

}

?>