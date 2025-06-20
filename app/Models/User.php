<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserGender;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'fullname',
        'phone',
        'email',
        'password',
        'status',
        'gender',
        'role',
        'avatar',
        'is_verified',
        'birthday'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'birthday' => 'date',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => UserStatus::class,
        'gender' => UserGender::class,
        'role' => UserRole::class,
    ];

    // Relationships
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'userID');
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
    
    public function followedShops()
    {
        return $this->belongsToMany(Shop::class, 'shop_followers', 'followerID', 'shopID')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Methods
    public function isAdmin()
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isCustomer()
    {
        return $this->role === UserRole::CUSTOMER;
    }

    public function isSeller()
    {
        return $this->role === UserRole::SELLER;
    }

    public function isEmployee()
    {
        return $this->role === UserRole::EMPLOYEE;
    }

    public function isActive()
    {
        return $this->status === UserStatus::ACTIVE;
    }

    public function isBanned()
    {
        return $this->status === UserStatus::BANNED;
    }

    public function getDefaultAddress()
    {
        return $this->addresses()->where('is_default', true)->first();
    }
    public function getGenderLabel(): string
    {
        return match ($this->gender->value ?? null) {
            'male' => 'Nam',
            'female' => 'Nữ',
            'other' => 'Khác',
            default => 'Không xác định',
        };
    }
}
