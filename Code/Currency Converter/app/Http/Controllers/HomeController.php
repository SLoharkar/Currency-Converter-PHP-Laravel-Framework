<?php

namespace App\Http\Controllers;

use App\Http\Requests\HomeController\LoginRequest;
use App\Http\Requests\HomeController\RegisterRequest;
use App\Http\Requests\HomeController\ForgotPasswordRequest;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\HomeService;




class HomeController {


    private $homeService;



    /**
     * Create a new controller instance.
     *
     * @param HomeService $homeService
     * @return void
    */
    public function __construct(HomeService $homeService){
        $this->homeService = $homeService;
        Log::info('HomeController instance created.');
    }

    

    /**
     * Display the homepage.
     *
     * This method checks if the request's IP address is authorized to access the homepage.
     * If the IP address is not authorized, an error message is flashed to the session and a warning is logged.
     * Otherwise, the homepage view is returned.
     *
     * @return The homepage view.
    */
    public function homepage(){
    
        Log::info('Displaying the homepage form.');

        // Get the IP address of the incoming request
        $ipAddress = request()->ip();

        // Validate the IP address using the HomeService
        if (!$this->homeService->validateIp($ipAddress)){
            $message = "Unauthorized IP address access: Admins only. IP: {$ipAddress}";

            session()->flash('error', $message);
            Log::warning($message);

            // Return the homepage view with the error message
            return view('homepage');
        }

        Log::info("Access to homepage from IP: {$ipAddress}");

        return view('homepage');
    }



    /**
     * Display the login form.
     * 
     * @return The login view with the last attempted username
    */
    public function showLoginForm(){

        Log::info('Displaying the login form.');

        // Retrieve the last attempted username from the session, defaulting to an empty string if it doesn't exist
        $lastUsername = session('last_username', '');

        Log::debug('Last attempted username: ' . $lastUsername);

        return view('login')->with(['last_username' => $lastUsername]);
    }



    /**
     * Handle an incoming login request.
     *
     * This method attempts to authenticate a user using the provided credentials.
     * If authentication is successful, the user is redirected to the dashboard.
     * If authentication fails, an error message is flashed to the session and
     * the user is redirected back to the login form with the last attempted username.
     *
     * @param  \App\Http\Requests\HomeController\LoginRequest  $request
     * @return RedirectResponse  A redirect response to the appropriate route based on the login outcome.
    */
    public function login(LoginRequest $request){

        $lastUsername = $request->input('username');

        Log::info('Login attempt for username: ' . $lastUsername);

        // Attempt to login using the homeService
        if ($this->homeService->login($request)){

            Log::info('Login successful for username: ' . $lastUsername);    

            // Redirect to the dashboard route on successful login
            return redirect()->route('dashboard');
        } else {

            Log::warning('Failed login attempt for username: ' . $lastUsername);

            session()->flash('error', "Failed login attempt for username: " . $lastUsername);

            // Redirect back to the login form with the last attempted username
            return redirect()->route('showLogin')->with(['last_username' => $lastUsername]);
        }
    }



    /**
     * Display the registration form.
     * 
     * @return The view for the registration form.
    */
    public function showRegisterForm(){
        Log::info('Displaying the registration form.');
        return view('register');
    }



    /**
     * Handle an incoming registration request.
     *
     * This method registers a new user using the provided username and password.
     * If registration is successful, a success message is flashed to the session and
     * the user is redirected to the login form. If registration fails, an error
     * message is flashed to the session and the user is redirected back to the
     * registration form.
     *
     * @param  \App\Http\Requests\HomeController\RegisterRequest  $request
     * @return RedirectResponse  A redirect response to the appropriate route based on the registration outcome.
     *
     * @throws \Exception  If an error occurs during the registration process.
    */
    public function register(RegisterRequest $request){

        // Extract username and password from the request
        $username = $request->input('username');
        $password = $request->input('password');

        Log::info('Registration attempt for username: ' . $username);

        try {

            // Attempt to register the user using the homeService
            $this->homeService->register($username, $password);

            Log::info('Registration successful for username: ' . $username);

            session()->flash('success', 'Registration successful! You can now log in as ' . htmlspecialchars($username));

            // Redirect to the login form
            return redirect()->route('showLogin');
        } catch (\Exception $e){

            Log::error('Registration failed for username: ' . $username . ' - Error: ' . $e->getMessage());

            session()->flash('error', 'Registration failed: ' . $e->getMessage());

            // Redirect back to the registration form
            return redirect()->route('showRegister');
        }
    }



     /**
     * Display the forgot password form.
     *
     * @return The view for the forgot password form.
    */ 
    public function showForgotPasswordForm(){
        Log::info('Displaying the forgot password form.');
        return view('forgot_password');
    }



    /**
     * Handle a forgot password request.
     *
     * This method handles the forgot password request by resetting the user's password
     * if the provided credentials are correct. If the reset is successful, a success message
     * is flashed to the session and the user is redirected to the login form. If validation fails,
     * the user is redirected back with validation errors. If any other exception occurs, an error
     * message is flashed to the session.
     *
     * @param  \App\Http\Requests\HomeController\ForgotPasswordRequest  $request
     * @return RedirectResponse  A redirect response to the appropriate route based on the reset outcome.
     *
     * @throws \Illuminate\Validation\ValidationException  If validation of the request fails.
     * @throws \Exception  If an error occurs during the password reset process.
    */
    public function forgotPassword(ForgotPasswordRequest $request){

        // Extract inputs from the request
        $username = $request->input('username');
        $extPassword = $request->input('ext_password');
        $newPassword = $request->input('new_password');

        Log::info('Forgot password attempt for username: ' . $username);

        try {

            // Attempt to reset the user's password using the homeService
            $this->homeService->resetPassword($username, $extPassword, $newPassword);

            Log::info('Password reset successful for username: ' . $username);

            session()->flash('success', 'Password reset successfully');

            // Redirect to the login form
            return redirect()->route('showLogin');
        } catch (ValidationException $e){

            Log::warning('Validation errors for forgot password attempt for username: ' . $username, $e->errors());

            // Redirect back with validation errors and old input
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e){

            Log::error('Password reset failed for username: ' . $username . ' - Error: ' . $e->getMessage());

            session()->flash('error', $e->getMessage());

            // Redirect back to the forgot password form
            return redirect()->route('showForgotPassword');
        }
    }



    /**
     * Display the dashboard for the authenticated user.
     *
     * This method retrieves the dashboard data from the homeService and checks
     * if the user has access. If access is granted, a success message is flashed
     * to the session and the dashboard view is returned with the user's role and username.
     * If access is denied, the user is redirected to the home route.
     *
     * @return View|RedirectResponse
    */
    public function dashboard(){

        try {
            // Retrieve dashboard data from the homeService
            $dashboardData = $this->homeService->dashboard();

            // Check if the user has access to the dashboard
            if ($dashboardData['access']){

                session()->flash('success', 'Welcome, ' . $dashboardData['username'] . '!');

                // Return the dashboard view with the user's role and username
                return view('dashboard', ['role' => $dashboardData['role'], 'username' => $dashboardData['username']]);
            } else {
                // Redirect to the home route
                return redirect()->route('home');
            }
        }catch(\Exception $e){
            Log::error('An error occurred while checking dashboard access: ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            return redirect()->route('showLogin');
        }
    }



    /**
     * Log the user out of the application.
     *
     * This method handles the user logout process. It calls the `logout` method
     * on the `homeService` to perform the logout operations. After logging out,
     * it flashes a success message to the session and redirects the user to the login form.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request instance containing user data.
     * @return RedirectResponse  A redirect response to the login route after logging out.
    */
    public function logout(Request $request){

        // Perform the logout operation using the homeService
        $this->homeService->logout($request);    

        session()->flash('success', 'You have been successfully logged out.');
        
        // Redirect to the login form
        return redirect()->route('login');
    }

}

?>