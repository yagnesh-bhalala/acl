<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Hash;

/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
*/
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'remember_token', 'created_by', 'rl','start_access_date', 'end_access_date', 'phone', 'pack', 'username', 'status', 'pwd'];
    
    
    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
    
    
}
