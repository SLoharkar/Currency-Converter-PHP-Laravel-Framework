<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string|null $plainPassword
 * @property string|null $rememberToken
 * @property array $roles
 */
class User extends Authenticatable{


    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'plain_password',
        'roles',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'plain_password',
        'remember_token',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'roles' => 'array',
    ];


    /**
     * Hash the user's password before saving it.
     *
     * @param  string  $password
     * @return void
     */
    public function setPasswordAttribute($password){
        $this->attributes['password'] = Hash::make($password);
    }


    /**
     * Get the roles of the user.
     *
     * @return array
     */
    public function getRolesAttribute($value){
        return json_decode($value, true);
    }


    /**
     * Set the roles of the user.
     *
     * @param  array  $roles
     * @return void
    */
    public function setRolesAttribute($roles){
        $this->attributes['roles'] = json_encode($roles);
    }


    /**
     * Check if the user has a specific role.
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role){
        return in_array($role, $this->roles);
    }


    /**
     * Get the identifier that will be stored in the session.
     *
     * @return string
     */
    public function getAuthIdentifierName(){
        return 'username'; // For Laravel, typically you use an ID or email
    }


    /**
     * Get the password for authentication.
     *
     * @return string
     */
    public function getAuthPassword(){
        return $this->password;
    }


    /**
     * Get the password for authentication.
     *
     * @return string
     */
    public function getAuthIdentifier(){
        return $this->username;
    }
}

?>