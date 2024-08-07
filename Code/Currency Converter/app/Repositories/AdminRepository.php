<?php

namespace App\Repositories;

use App\Models\User;


class AdminRepository{


    /**
     * Retrieve all users from the database.
    */
    public function getAllUsers(){
        return User::all();
    }


    /**
     * Retrieve a user by their ID.
     * @param  int  $id  The ID of the user to retrieve.
     * @return \App\Models\User|null  The User model or null if not found.
    */
    public function getUserById($id): ?User{
        return User::find($id);
    }


    /**
     * Delete a user from the database.
     * @param  \App\Models\User  $user  The User model to delete.
     * @return void
    */
    public function deleteUser(User $user){
        $user->delete();
    }


    /**
     * Find a user by their username.
     * @param  string  $username  The username to search for.
     * @return \App\Models\User|null  The User model or null if not found.
    */
    public function findByUsername(string $username): ?User{
        return User::where('username', $username)->first();
    }


    /**
     * Update a user in the database.
     * @param  \App\Models\User  $user  The User model with updated information.
     * @return bool  True on success, false on failure.
    */
    public function updateUser(User $user){
        return $user->save();
    }

}

?>