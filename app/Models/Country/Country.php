<?php

namespace App\Models\Country;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use Database\Factories\CountryFactory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/*
 * Страна создается в CountryFactory и CountriesSeeder
 * По умолчанию страна Россия
 * Телефоны проходят валидацию по регулярному выражению.
 * В форме в поле phone применяется маска на основе регулярного выражения в поле phone_regex
 * */
class Country extends Model
{
    /** @use HasFactory<CountryFactory> */
    use HasFactory,Notifiable;

    protected $fillable = [
        'code',
        'name',
        'iso_code',
        'phone_code',
        'currency',
        'active',
    ];

    public function companies(): HasMany|Country
    {
        return $this->hasMany(Company::class, 'country_id', 'id');
    }

    public function generatePhoneNumber(): string
    {
        if ($this->phone_regex) {
            return app(Generator::class)->regexify($this->phone_regex);
        }
        return app(Generator::class)->phoneNumber;
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
