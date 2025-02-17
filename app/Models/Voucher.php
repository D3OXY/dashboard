<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Voucher
 * @package App\Models
 */
class Voucher extends Model
{
    use HasFactory, LogsActivity;

    /**
     * @var string[]
     */
    protected $fillable = [
        'memo',
        'code',
        'credits',
        'uses',
        'expires_at',
    ];

    protected $dates = [
        'expires_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'credits' => 'float',
        'uses'    => 'integer'
    ];

    protected $appends = ['used', 'status'];

    /**
     * @return int
     */
    public function getUsedAttribute()
    {
        return $this->users()->count();
    }

    /**
     * @return string
     */
    public function getStatusAttribute()
    {
        return $this->getStatus();
    }

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function (Voucher $voucher) {
            $voucher->users()->detach();
        });
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        if ($this->users()->count() >= $this->uses) return 'USES_LIMIT_REACHED';
        if (!is_null($this->expires_at)) {
            if ($this->expires_at->isPast()) return 'EXPIRED';
        }

        return 'VALID';
    }

    /**
     * @param User $user
     * @return float
     * @throws Exception
     */
    public function redeem(User $user)
    {
        try {
            $user->increment('credits', $this->credits);
            $this->users()->attach($user);
            $this->logRedeem($user);
        } catch (Exception $exception) {
            throw $exception;
        }

        return $this->credits;
    }

    /**
     * @param User $user
     * @return null
     */
    private function logRedeem(User $user)
    {
        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->log('redeemed');

        return null;
    }
}
