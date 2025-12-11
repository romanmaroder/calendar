<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Client extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected string $field = 'field';
    protected string $header = 'header';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'avatar',
        'name',
        'surname',
        'middleName',
        'phone',
        'email',
        'comment',
        'blacklist',
        'prepayment',
        'discount',
        'records',
        'total',
        'source',
        'password',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'email_verified_at'
    ];

    /**
     * Если значение не передано или пусто — используем дефолтное
     * @param $value
    */
    public function setPasswordAttribute($value): void
    {
        $default = config('auth.defaults.password', 'user_password');
        $hashed = Hash::make(empty($value) ? $default : $value);

        $this->attributes['password'] = $hashed;
        $this->syncOriginal('password'); // важно для сохранения
    }

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
            'created_at' => 'datetime:d/m/Y H:i',
            'deleted_at' => 'datetime:d/m/Y H:i',
            'blacklist' => 'boolean',
            'prepayment' => 'boolean',
            'discount' => 'integer',
        ];
    }
}
