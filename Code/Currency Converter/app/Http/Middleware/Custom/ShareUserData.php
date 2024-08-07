<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\User;


class ShareUserData{

    /**
     * Handle an incoming request.
     *
     * This middleware checks if the users' table is empty and logs out the user if true.
     * If the user is authenticated, it shares the user's role and username with all views.
     * It logs each step of the process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
    */
    public function handle($request, Closure $next){

        Log::info('Handling request in ShareUserData middleware.');


        // Check if the users' table is empty
        if (User::count() == 0){

            Log::warning('Users table is empty. Logging out user.');

            // Log out the user and redirect to login with an error message
            Auth::logout();

            session()->flash('error', 'No users found in the database. You have been logged out.');

            return redirect()->route('showLogin');
        }

        // Check if the user is authenticated.
        if (Auth::check()){

            $user = Auth::user();
            $role = $user->hasRole('ROLE_ADMIN') ? 'admin' : 'user';

            Log::info('Authenticated user.', ['user_id' => $user->id, 'role' => $role]);

            // Share the 'role' and 'username' variables with all views
            view()->share([
                'role' => $role,
                'username' => $user->username,
            ]);

            Log::info('Shared role and username with views.', ['role' => $role, 'username' => $user->username]);

            return $next($request);
        } else{

            Log::warning('Session Expired. Please log in again.');

            session()->flash('error', 'Session Expired. Please log in again.');      

            return redirect()->route('showLogin');
        }
        
    }
}

?>