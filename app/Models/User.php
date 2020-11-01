<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The table associated with the entity.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'name',
        'role',
        'dob',
        'priority',
        'last_read_only_time',
    ];

    /**
     * Check if current user is admin
     *
     * @return boolen
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
