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


    protected $fillable = ['name','phone','status','description','contact','avatar','company_id'];
    protected  $guarded = [];

    protected $appends = ['resolved_country_id'];

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

    public function getResolvedCountryIdAttribute(): ?int
    {
        if ($this->company && $this->company?->country) {
            return $this->company->country->id;
        }

        return null;
    }
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y/m/d H:i',
            'status' => 'boolean',
        ];
    }

}
