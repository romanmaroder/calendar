<?php

namespace App\Models\Company;

use App\Models\Country\Country;
use Database\Factories\Company\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name','contact','description','info','avatar','country_id'
    ];
    protected $guarded = [];


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y/m/d H:i',
        ];
    }
}
