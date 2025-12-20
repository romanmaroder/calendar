<?php

namespace App\Models\Branch;

use App\Casts\Ucfirst;
use App\Models\User;
use Database\Factories\Branch\BranchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Branch extends Model
{
    /** @use HasFactory<BranchFactory> */
    use HasFactory, Notifiable, SoftDeletes;


    protected $fillable = ['name','status','description','contact','avatar' ];
    protected  $guarded = [];
    /**
     * Получить пользователей, которые принадлежат этому филиалу.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getStatus(): string
    {
        return $this->status ? 'active' : 'disabled';
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y/m/d H:i',
            'status' => 'boolean',
            'name' => Ucfirst::class,
            'description' => Ucfirst::class,
        ];
    }

}
