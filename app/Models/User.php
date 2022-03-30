<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;


    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'photo',
        'role_id',
        'job_title',
        'last_seen'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen' => 'datetime',
        //'role_id' => RoleEnum::class
    ];

    public function isSuperAdmin(): Attribute
    {
        return new Attribute(
            get: fn () => $this->role_id == RoleEnum::SUPER_ADMIN->value,
        );
    }

    public function isAdmin(): Attribute
    {
        return new Attribute(
            get: fn () => $this->role_id == RoleEnum::ADMIN->value,
        );
    }

    public function isAgent(): Attribute
    {
        return new Attribute(
            get: fn () => $this->role_id == RoleEnum::AGENT->value,
        );
    }

    public function isOnline(): Attribute
    {
        // is logged in 1 minutes ago
        return new Attribute(
            get: fn () => $this->last_seen >= Carbon::now()->subMinutes(1) && $this->last_seen <= Carbon::now()
        );
        //return cache()->has('user-is-online-' . $this->id);
    }

    public function scopeOnline($query)
    {
        //where logged in 1 minutes ago
        return $query->where('id', '!=', auth()->user()->id)->whereBetween('last_seen', [Carbon::now()->subMinutes(1), Carbon::now()]);
    }

    public function scopeSuperAdmins($query)
    {
        //where logged in 2 minutes ago
        return $query->where('role_id', RoleEnum::SUPER_ADMIN->value)
            ->where('id', '!=', auth()->user()->id);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'agent_id');
    }

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'users.' . $this->id;
    }
}
