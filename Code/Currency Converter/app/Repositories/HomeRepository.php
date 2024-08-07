<?php

namespace App\Repositories;

use App\Models\User;


class HomeRepository{



    /**
     * Register a new user.
     *
     * This method will save the given user to the database.
     *
     * @param  \App\Models\User  $user  The user instance to register.
     * @return void
    */    
    public function register(User $user){
        $user->save();
    }



    /**
     * Find a user by their username.
     *
     * This method searches for a user in the database by their username.
     *
     * @param  string  $username  The username to search for.
     * @return \App\Models\User|null  The user instance if found, null otherwise.
    */
    public function findByUsername(string $username): ?User{
        return User::where('username', $username)->first();
    }



    /**
     * Reset the password for a user.
     *
     * This method will save the given user to the database after a password reset.
     *
     * @param  \App\Models\User  $user  The user instance whose password is being reset.
     * @return void
    */
    public function resetPassword(User $user){
        $user->save();
    }


    /**
     * Logout a user.
     *
     * This method will logout the given user and invalidate the remember token.
     *
     * @param  \App\Models\User  $user  The user instance to register.
     * @return void
    */    
    public function logout(User $user){
        $user->save();
    }



}
?>