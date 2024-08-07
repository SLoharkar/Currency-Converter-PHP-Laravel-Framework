<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Repositories\AdminRepository;


class AdminService{


    protected $adminRepository;



    /**
     * Create a new instance of the class.
     *
     * This constructor injects the AdminRepository dependency and logs the initialization of the class.
     *
     * @param  \App\Repositories\AdminRepository  $adminRepository  The admin repository instance.
     * @return void
    */
    public function __construct(AdminRepository $adminRepository){

        Log::info('Initializing class with AdminRepository.');

        // Assign the injected AdminRepository to a class property
        $this->adminRepository = $adminRepository;
    }



    /**
     * Retrieve all users from the admin repository.
     *
     * This method fetches all users using the AdminRepository and logs the outcome.
     * It returns an array indicating whether users were found and the list of users if available.
     *
     * @return array  An array containing the status and the list of users if available.
    */
    public function getAllUsers(){

        Log::info('Attempting to retrieve all users.');

        // Fetch all users from the admin repository
        $users =  $this->adminRepository->getAllUsers();

        if (!empty($users)){

            Log::info('Users retrieved successfully.', ['user_count' => count($users)]);

            // Return the data indicating users were found
            return [
                'data' => true,
                'users' => $users
            ];    
        }
    }



    /**
     * Delete a user by their ID.
     *
     * This method retrieves a user by ID from the admin repository, checks if the user exists,
     * and verifies that the user is not the currently authenticated user. If all checks pass, 
     * it deletes the user and logs the outcome.
     *
     * @param  int  $id  The ID of the user to delete.
     * @return void
     * @throws \Exception  If the user is not found or if attempting to delete the currently authenticated user.
    */
    public function userDeleteById($id){

        Log::info('Attempting to delete user.', ['user_id' => $id]);

        // Retrieve the user by ID from the admin repository
        $user = $this->adminRepository->getUserById($id);

        if (!$user){
            Log::error('User not found for deletion.', ['user_id' => $id]);
            throw new \Exception('User not found');
        }

        $currentUser = Auth::user();

        // Check if the user to be deleted is the currently authenticated user
        if ($currentUser->id === $user->id){
            Log::error('Attempt to delete the authenticated user.', ['user_id' => $user->id]);
            throw new \Exception('You cannot delete yourself.');
        }

        // Delete the user from the repository
        $this->adminRepository->deleteUser($user);

        Log::info('User deleted successfully.', ['user_id' => $user->id]);
    }



    /**
     * Retrieve a user by their ID.
     *
     * This method fetches a user from the admin repository by ID and returns an array indicating
     * the status of the retrieval and the user data if found. It logs the outcome of the retrieval.
     *
     * @param  int  $id  The ID of the user to retrieve.
     * @return array  An array containing the status of the retrieval and user data if found.
    */
    public function getUserById($id){

        Log::info('Attempting to retrieve user by ID.', ['user_id' => $id]);

        // Retrieve the user by ID from the admin repository
        $user = $this->adminRepository->getUserById($id);

        if($user){
            
            Log::info('User retrieved successfully.', ['user_id' => $id]);
            
            // Return the status and user data            
            return [
                'status' => true,
                'user' => $user
            ];
        } else{

            Log::info('User not found.', ['user_id' => $id]);
            
            // Return the status indicating user not found
            return [
                'status' => false
            ];
        }
    }




    /**
     * Update a user by their ID.
     *
     * This method updates a user's details including username, password, and roles.
     * It checks for username conflicts, updates the user information, and handles 
     * role changes. It logs the process and outcomes of the update operation.
     *
     * @param  string  $username  The new username for the user.
     * @param  string  $password  The new password for the user.
     * @param  array   $roles     The new roles for the user.
     * @param  int     $updateId  The ID of the user to update.
     * @return array   An array containing the status of the update and whether a role change occurred.
     * @throws \Illuminate\Validation\ValidationException  If the username is already taken by another user.
    */
    public function updateUserById($username, $password, $roles, $updateId){

        Log::info('Attempting to update user.', ['user_id' => $updateId, 'username' => $username]);

        // Retrieve the user to be updated
        $updateUser = $this->adminRepository->getUserById($updateId);

        // Check if the username is already taken by another user
        $existingUser = $this->adminRepository->findByUsername($username);

        if ($existingUser && $existingUser->id !== $updateUser->id){

            Log::warning('Username conflict detected.', ['username' => $username, 'existing_user_id' => $existingUser->id]);

            // Throw a validation exception if the username is taken
            throw ValidationException::withMessages([
                'username' => 'Username already taken.'
            ]);
        }

        // Update user details
        $updateUser->username = $username;
        $updateUser->password = $password;  //The User model will handle hashing
        $updateUser->plain_password = $password;
        $updateUser->roles = $roles;

        // Perform the update operation
        $status = $this->adminRepository->updateUser($updateUser);

        // Determine if the user's role has changed
        $currentUser = Auth::user();
        $roleChanged = !in_array('ROLE_USER', $currentUser->roles) && in_array('ROLE_USER', $roles);

        if($status){

            Log::info('User updated successfully.', ['user_id' => $updateId]);

            // If updating the current authenticated user, re-authenticate them
            if ($currentUser->id === $updateUser->id){

                Log::info('Re-authenticated the current user after update.', ['user_id' => $updateId]);

                Auth::login($updateUser);

                return [
                    'status' => true,
                    'roleChange' => $roleChanged
                ];
            }

            return [
                'status' => true,
                'roleChange' => false
            ];
        }

    }

}

?>