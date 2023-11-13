<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;

class User extends Authenticatable implements HasApiTokensContract
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    //A user belongs to one website
    public function website():belongsTo
    {
        return $this->belongsTo(Website::class);
    }

    // Method to get the bearer token and admin URL
    public function getAdminDetails()
    {
        // Generate a token for the user
        $tokenResult = $this->createToken('admin_access');
        $token = $tokenResult->plainTextToken;

        // Retrieve the admin URL from the related website
        // Ensure that your User model has a relationship with the Website model that includes the admin URL
        $adminUrl = $this->website->url ?? 'http://localhost/dashboard';

        return [
            'token' => $token,
            'admin_url' => $adminUrl,
        ];
    }
}
