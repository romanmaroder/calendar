<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Casts\Ucfirst;
use App\Models\Branch\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'comment',
        'birthday',
        'email',
        'branch_id',
        'password',
    ];
    protected  $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * Получить филиал, которому принадлежит пользователь.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Если значение не передано или пусто — используем дефолтное
     * @param $value
     */
    public function setPasswordAttribute($value): void
    {
        $default = config('auth.defaults.password', 'password1');
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
            'created_at' => 'datetime:Y/m/d H:i',
            'birthday' => 'date:Y-m-d',
            'blacklist' => 'boolean',
            'prepayment' => 'boolean',
            'discount' => 'integer',
            'name' => Ucfirst::class,
            'middleName' => Ucfirst::class,
            'surname' => Ucfirst::class
        ];
    }

}
