<?php

namespace App\Models\Branch;

use App\Models\Company\Company;
use App\Models\User;
use Database\Factories\Branch\BranchFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;


/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $description
 * @property string $contact
 * @property int $status
 * @property int $company_id
 * @property int|null $country_id
 * @property string|null $avatar
 * @property Collection|null $users
 */
class Branch extends Model
{
    /** @use HasFactory<BranchFactory> */
    use HasFactory, Notifiable, SoftDeletes;


    protected $fillable = ['name','phone','status','description','contact','avatar','company_id','country_id'];
    protected  $guarded = [];

    // Массив для хранения виртуальных атрибутов
    protected array $virtualAttributes = [];

    /**
     * Получить пользователей, которые принадлежат этому филиалу.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class,'branch_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function getStatus(): string
    {
        return $this->status ? 'active' : 'disabled';
    }

    /**
     * Сеттер для виртуального поля country_id
     */
    public function setCountryIdAttribute($value): void
    {
        $this->virtualAttributes['country_id'] = $value;
    }

    /**
     * Геттер для виртуального поля country_id
     */
    public function getCountryIdAttribute(): ?int
    {
        // Сначала проверяем виртуальный атрибут
        if (isset($this->virtualAttributes['country_id'])) {
            return $this->virtualAttributes['country_id'];
        }
        // Если есть компания и у неё есть страна — возвращаем её ID
        if ($this->company && $this->company->country) {
            return $this->company->country->id;
        }
        return null;
    }

    /**
     * Исключаем виртуальные поля из массива атрибутов модели
     */
    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();
        unset($attributes['country_id']);
        return $attributes;
    }

    /**
     * При сериализации в JSON включаем виртуальное поле
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        $array['country_id'] = $this->country_id;
        return $array;
    }



    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y/m/d H:i',
            'status' => 'boolean',
        ];
    }

}
