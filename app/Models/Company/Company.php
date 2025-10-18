<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\Company\CompanyFactory> */
    use HasFactory;
    protected $fillable = [];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:d/m/Y H:i',
        ];
    }
}
