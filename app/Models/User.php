<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; 
/**
 * @method $this assignRole(...$roles)
 * @method bool hasRole(string|array|\Spatie\Permission\Models\Role $roles, string $guard = null)
 * @method \Spatie\Permission\Models\Role[] getRoleNames()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;
    protected string $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   protected $fillable = [
        'nombre','apellido','correo_electronico','password', 'ci','telefono','email_verified_at','remember_token','administrador', 'activo','id_rol',
    ];
     public function getEmailForPasswordReset()
    {
        return $this->correo_electronico;
    }


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
            'administrador' => 'boolean',
        ];
    }
   public function getAdminlteProfileAttribute()
    {
        return trim($this->nombre . ' ' . $this->apellido);
    }
    public function getNameAttribute()
    {
        return trim($this->nombre . ' ' . $this->apellido);
    }
      public function rol()
    {
        return $this->belongsTo(\App\Models\Rol::class, 'id_rol', 'id_rol');
    }

    public function conductor()
    {
        return $this->hasOne(\App\Models\Conductor::class, 'ci', 'ci');
    }

}
