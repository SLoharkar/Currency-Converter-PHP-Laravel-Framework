<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Validation\ValidationException;

use App\Models\User;
use App\Repositories\HomeRepository;


class HomeService{


    private $homeRepository;
    private $iPService;



    /**
     * Create a new service instance.
     *
     * This constructor initializes the HomeService with instances of HomeRepository
     * and IPService. These dependencies are injected via Laravel's service container.
     *
     * @param  \App\Repositories\HomeRepository  $homeRepository  The repository responsible for accessing home-related data.
     * @param  \App\Services\IPService  $iPService  The service responsible for handling IP-related functionality.
    */
    public function __construct(HomeRepository $homeRepository, IPService $iPService){

        Log::info('HomeService initialized with HomeRepository and IPService.');

        // Assign the injected HomeRepository to a class property
        $this->homeRepository = $homeRepository;

        // Assign the injected IPService to a class property
        $this->iPService = $iPService;
    }



    /**
     * Attempt to log the user into the application.
     *
     * This method handles the login process by attempting to authenticate the user
     * with the provided credentials (username and password). If the authentication is
     * successful, the user's session is regenerated to prevent session fixation attacks,
     * and the method returns true. If authentication fails, the method returns false.
     *
     * @param  $request  The HTTP request instance containing user credentials.
     * @return bool  True if authentication is successful, false otherwise.
    */
    public function login($request){

        // Extract the credentials from the request
        $credentials = $request->only('username', 'password');

        Log::info('Login attempt for username: ' . $credentials['username']);

        // Check if the 'remember' checkbox is selected in the request
        $remember = $request->has('remember');

        // Attempt to authenticate the user with the provided credentials
        // The second parameter ($remember) indicates whether to remember the user or not
        if (Auth::attempt($credentials, $remember)){

            // Regenerate the session to prevent session fixation attacks
            $request->session()->regenerate();

            Log::info('Login successful for username: ' . $credentials['username']);

            return true;
        } else{

            Log::warning('Login failed for username: ' . $credentials['username']);

            return false;
        }
    }



    /**
     * Register a new user in the application.
     *
     * This method handles the registration process by creating a new `User` model instance,
     * setting its properties, and then saving it to the database. The `User` model should handle
     * the hashing of the password automatically before saving it to the database.
     *
     * @param  string  $username  The username of the user to register.
     * @param  string  $password  The password of the user to register (in plain text).
     * 
     * @return void
    */
    public function register($username, $password){

        Log::info('Attempting to register user with username: ' . $username);

        // Create a new User model instance and set values
        $user = new User();
        $user->username = $username;
        $user->password = $password;    //The User model will handle hashing
        $user->plain_password = $password;
        $user->roles = ['ROLE_USER'];

        // Save the User model to the database
        $this->homeRepository->register($user);

        Log::info('User registered successfully with username: ' . $username);
    }



    /**
     * Reset the user's password in the application.
     *
     * This method handles the process of resetting a user's password. It first verifies the provided
     * existing password against the stored hashed password. If the existing password is correct, it
     * updates the user's password with the new password (in plain text), and the `User` model will handle
     * hashing the new password automatically before saving it to the database.
     *
     * @param  string  $username      The username of the user whose password is being reset.
     * @param  string  $extPassword   The current password of the user, provided for verification.
     * @param  string  $newPassword   The new password to be set for the user (in plain text).
     * 
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException  If the existing password is incorrect.
    */
    public function resetPassword($username, $extPassword, $newPassword){

        Log::info('Attempting to reset password for username: ' . $username);

        // Retrieve the user by username from the repository
        $user = $this->homeRepository->findByUsername($username);
        
        // Check if the provided existing password matches the stored hashed password
        if (!Hash::check($extPassword, $user->password)){

            Log::warning('Password reset failed for username: ' . $username . '. Existing password incorrect.');

            throw ValidationException::withMessages([
                'ext_password' => 'The existing password you entered is incorrect.'
            ]);
        }

        // Update the user's password with the new plain-text password
        $user->password = $newPassword;     //The User model will handle hashing
        $user->plain_password = $newPassword;

        // Save the updated user to the repository
        $this->homeRepository->resetPassword($user);

        Log::info('Password reset successfully for username: ' . $username);
    }



    /**
     * Retrieve the dashboard access information for the currently authenticated user.
     *
     * This method checks if the user is authenticated, determines their role, and validates
     * their IP address. If the user is not authenticated or their IP address is invalid, 
     * it logs them out and denies access. The method returns an array containing access status,
     * user role, and username if access is granted.
     *
     * @return array  An associative array with the following keys:
     *                - 'access' (bool): Indicates if the user has access to the dashboard.
     *                - 'role' (string, optional): The role of the user ('admin' or 'user').
     *                - 'username' (string, optional): The username of the authenticated user.
    */
    public function dashboard(){

        // Check if the user is authenticated
        if (!Auth::check()) {
            throw new \Exception("Session Expired. Please log in again.");
        }

        // Get the currently authenticated user
        $user = Auth::user();

        Log::info('Dashboard attempt for username: ' . $user->username);

        // If no user is authenticated, deny access
        if (!$user){
            Log::warning('Dashboard access denied: No authenticated user.');
            return ['access' => false];
        }

        // Determine the user's role
        $role = 'user'; // Default role
        if ($user->hasRole('ROLE_ADMIN')){
            $role = 'admin';
        }
        
        // For non-admin users, validate their IP address
        if (!$this->validateIp(request()->ip()) && $role != 'admin'){
            Log::warning('Dashboard access denied for username: ' . $user->username . '. Invalid IP address.');
            // Log out the user and deny access
            Auth::logout();
            return ['access' => false];
        }

        Log::info('Dashboard access granted for username: ' . $user->username);

        // Return the access information
        return [
            'access' => true,
            'role' => $role,
            'username' => $user->username
        ]; 
    
    }

    

    /**
     * Log out the currently authenticated user.
     *
     * This method will log out the currently authenticated user, invalidate the session,
     * and regenerate the session token. It ensures that the user is properly logged out
     * and any session data is cleared.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
    */
    public function logout($request){
        
        // Log the user logout event
        Log::info('User logged out.', ['user_id' => Auth::id()]);

        // Get the currently authenticated user
        $user = Auth::user();

        if ($user) {
            // Invalidate the remember token
            $user->remember_token = null;
            $this->homeRepository->logout($user);
        }

        // Perform the logout operation
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate the session token
        $request->session()->regenerateToken();

    }



    /**
     * Validate the given IP address.
     *
     * This method will use the IPService to validate the given IP address.
     *
     * @param  string  $ip  The IP address to validate.
     * @return bool         True if the IP address is valid, false otherwise.
    */
    public function validateIp($ip){

        Log::info('Validating IP address.'.$ip);
        
        // Validate the IP address using the IPService
        $isValid = $this->iPService->validateIp($ip);

        Log::info('IP validation result.', ['ip' => $ip, 'is_valid' => $isValid]);

        return $isValid;
    }

}
?>