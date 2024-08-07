<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\AdminController\UserUpdateRequest;
use Illuminate\Http\Request;

use App\Services\AdminService;



class AdminController{


    private $adminService;


    
    /** 
     * Create a new instance of the class.
     *
     * This constructor injects the AdminService dependency.
     *
     * @param  \App\Services\AdminService  $adminService  The admin service instance.
     * @return void
    */
    public function __construct(AdminService $adminService){

        Log::info('Initializing class with AdminService.');

        // Assign the injected AdminService to a class property
        $this->adminService = $adminService;
    }



    /**
     * Display the user management view.
     *
     * This method retrieves all users from the AdminService and displays the user management view.
     * It logs the retrieval of users and the result of the operation.
     *
    */
    public function userManagement(){

        Log::info('Retrieving all users.');

        // Retrieve all users from the AdminService
        $users = $this->adminService->getAllUsers();

        // Check if users were retrieved successfully
        if($users['data']){

            Log::info('Users retrieved successfully.', ['user_count' => count($users['users'])]);

            // Return the user management view with the retrieved users
            return view('user_management')->with([
                'users' => $users['users']
            ]);    
        } 
    }



    /**
     * Delete a user by their ID.
     *
     * This method retrieves the user ID from the request, attempts to delete the user using the AdminService,
     * and handles any exceptions that may occur during the process. It logs the outcome of the operation.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request containing the user ID.
     * @return RedirectResponse  Redirect response to the user management form.
    */
    public function userDeleteById(Request $request){

        // Retrieve the user ID from the request
        $id = $request->input('id');  

        try {

            Log::info('Attempting to delete user.', ['user_id' => $id]);

            // Attempt to delete the user by ID
            $this->adminService->userDeleteById($id);

            Log::info('User deleted successfully.', ['user_id' => $id]);

            session()->flash('success', 'User deleted successfully.');
        } catch (\Exception $e){
            
            Log::error('Error deleting user.', ['user_id' => $id, 'exception' => $e->getMessage()]);

            session()->flash('error', $e->getMessage());
        }

        // Redirect to the user management form
        return redirect()->route('admin.user_management_form');
    }



    /**
     * Display the user update form for a specific user.
     *
     * This method retrieves the user ID from the request or session, fetches the user details
     * from the AdminService, and displays the user update form. It logs the actions and outcomes.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request containing the user ID.
     * @return The view for user update, or null if the user is not found.
    */
    public function showUserUpdate(Request $request){

        // Retrieve the user ID from the request or session
        $id = $request->input('id') ?? session('id');

        Log::info('Attempting to show user update form.', ['user_id' => $id]);

        // Fetch the user details by ID
        $user = $this->adminService->getUserById($id);

        if($user['status']){

            Log::info('User details retrieved successfully for update.', ['user_id' => $user['user']->id]);

            // Store the user ID in the session for future use
            session(['id' => $user['user']->id]);

            // Return the user update view with the user details
            return view('user_update')->with([
                'user' => $user['user'],
            ]);
        }else {
            Log::warning('Failed to retrieve user details.', ['user_id' => $id]);
        } 
    }



    /**
     * Update a user by their ID.
     *
     * This method updates user details including username, password, and roles.
     * It handles the update process, logs actions and outcomes, and manages role changes.
     *
     * @param  \App\Http\Requests\UserUpdateRequest  $request  The request instance containing user update data.
     * @return RedirectResponse  Redirect response based on the update status.
    */
    public function userUpdateById(UserUpdateRequest $request){

        // Retrieve input data from the request
        $username = $request->input('username');
        $password = $request->input('password');
        $roles = $request->input('roles');
        $updateId = $request->input('id');

        try {

            Log::info('Attempting to update user.', ['user_id' => $updateId, 'username' => $username]);

            // Update the user by ID using the AdminService
            $status = $this->adminService->updateUserById($username,$password,$roles,$updateId);

            if($status['status']){
                
                Log::info('User updated successfully.', ['user_id' => $updateId]);

                session()->flash('success', 'User updated successfully.');

                if ($status['roleChange']){

                    Log::info('User role updated. Prompting user to log in again.', ['user_id' => $updateId]);

                    session()->flash('success', 'User Role has been updated successfully. Please log in again.');

                    return redirect()->route('showLogin');
                }

                // Redirect to the user management form
                return redirect()->route('admin.user_management_form');
            }
        }catch(ValidationException $e){
            
            Log::error('Validation error during user update.', ['errors' => $e->errors()]);

            // Redirect back with input and validation errors
            return redirect()->back()->withInput()->withErrors($e->errors());
        }        

    }
        
}               

?>