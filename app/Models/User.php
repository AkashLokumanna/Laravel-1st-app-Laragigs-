<?php

namespace App\Models;

use App\Models\Listing;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens; // Added HasApiTokens for Sanctum

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Ensure role is fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships with listings
    public function listings() {
        return $this->hasMany(Listing::class,'user_id');
    }
    
    /**
     * Check if user has an admin role.
     *
     * @return bool
     */
    public function isAdmin() {
        return $this->role === 'admin';
    }

    /**
     * Check if user has an editor role.
     *
     * @return bool
     */
    public function isEditor() {
        return $this->role === 'editor';
    }

    /**
     * Check if user has a regular user role.
     *
     * @return bool
     */
    public function isUser() {
        return $this->role === 'user';
    }
}
