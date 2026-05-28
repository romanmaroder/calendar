<?php

namespace App\Models\Company;

use App\Casts\Ucfirst;
use App\Models\Branch\Branch;
use App\Models\Country\Country;
use Database\Factories\Company\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
* @property int $id
* @property string $name
* @property string $phone
* @property string $description
* @property string $contact
* @property string $info
* @property string|null $avatar
* @property int $country_id
*/
class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'contact',
        'description',
        'info',
        'avatar',
        'country_id'
    ];
    protected $guarded = [];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
    public function branches(): HasMany|Country
    {
        return $this->hasMany(Branch::class, 'company_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y/m/d H:i',
        ];
    }
}
