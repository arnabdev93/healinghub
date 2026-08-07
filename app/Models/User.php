<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\BookingSlot;
use App\Models\UserDetail;
use App\Models\DoctorGoogleToken;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bookingSlots()
    {
        return $this->hasMany(BookingSlot::class);
    }

    public function details()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function doctorAppointments()
    {
        return $this->hasMany(BookAppointment::class,'doctor_id');
    }
    // public function googleToken()
    // {
    //     return $this->hasOne(DoctorGoogleToken::class, 'doctor_id');
    // }
}
