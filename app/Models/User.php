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
        'birthday',
        'rank',
        'total_spent',
        'reset_code',
        'reset_code_expires_at'
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
        'total_spent' => 'decimal:2',
        'reset_code_expires_at' => 'datetime',
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

    public function seller()
    {
        // Sửa lại khóa ngoại thành 'userID' thay vì mặc định 'user_id'
        return $this->hasOne(Seller::class, 'userID');
    }

    public function autoChatSetting()
    {
        return $this->hasOne(\App\Models\AutoChatSetting::class, 'user_id');
    }

    public function followedShops()
    {
        return $this->belongsToMany(Shop::class, 'shop_followers', 'followerID', 'shopID')
            ->withTimestamps();
    }

    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class, 'ownerID', 'id');
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'userID');
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
   public function updateRank()
    {
        $ranks = [
            ['name' => 'iron', 'threshold' => 0],
            ['name' => 'bronze', 'threshold' => 1000000],
            ['name' => 'silver', 'threshold' => 5000000],
            ['name' => 'gold', 'threshold' => 10000000],
            ['name' => 'diamond', 'threshold' => 20000000],
            ['name' => 'supreme', 'threshold' => 50000000],
        ];

        $currentRank = 'iron';
        foreach ($ranks as $rank) {
            if (($this->total_spent ?? 0) >= $rank['threshold']) {
                $currentRank = $rank['name'];
            } else {
                break;
            }
        }

        $this->rank = $currentRank;
        $this->save();
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            Shop::class,
            'ownerID',   // trong bảng shops
            'shopID',   // trong bảng products
            'id',        // khóa chính của users
            'id'         // khóa chính của shops
        );
    }
}
