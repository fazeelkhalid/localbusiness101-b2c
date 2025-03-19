<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficeHour extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'digital_card_id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_off',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_off' => 'boolean',
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
    ];

    /**
     * Get the digital card that owns the office hour.
     */
    public function digitalCard(): BelongsTo
    {
        return $this->belongsTo(DigitalCard::class);
    }

    public static function saveOfficeHours(int $digitalCardId, array $officeHours): void
    {
        foreach ($officeHours as $day => $hours) {
            self::create([
                'digital_card_id' => $digitalCardId,
                'day_of_week' => $day,
                'open_time' => $hours['open_time'] ?? null,
                'close_time' => $hours['close_time'] ?? null,
                'is_off' => $hours['is_off'] ?? false,
            ]);
        }
    }
}
