<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];
    
    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a penulis
     */
    public function isPenulis()
    {
        return $this->role === 'penulis';
    }

    /**
     * Check if user is a pembaca
     */
    public function isPembaca()
    {
        return $this->role === 'pembaca';
    }

    /**
     * Check if user is an editor
     */
    public function isEditor()
    {
        return $this->role === 'editor';
    }
    
    /**
     * Get all posts for the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    /**
     * Get all comments for the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
