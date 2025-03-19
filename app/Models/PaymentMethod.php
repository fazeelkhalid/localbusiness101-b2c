<?php

namespace App\Models;

use App\Http\Utils\CustomUtils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'digital_card_id',
        'method_name',
        'description',
        'payment_identifier',
        'qr_code_image',
    ];

    public function digitalCard(): BelongsTo
    {
        return $this->belongsTo(DigitalCard::class);
    }

    public static function savePaymentMethods(int $digitalCardId, array $paymentMethods, $slug)
    {
        foreach ($paymentMethods as $key => $payment) {
            if (!empty($payment['qr_code_image'])) {
                $qrCodeImage = $payment['qr_code_image'];
                $qrCodeImageFilename = 'qr_code_image-' . time() . '.' . $qrCodeImage->getClientOriginalExtension();
                $paymentMethods[$key]['qr_code_image_url'] = CustomUtils::uploadCardImage('/' . $slug, $qrCodeImage, $qrCodeImageFilename);
            }

            self::create([
                'digital_card_id' => $digitalCardId,
                'method_name' => $payment['method_name'],
                'description' => $payment['description'] ?? null,
                'payment_identifier' => $payment['payment_identifier'] ?? null,
                'qr_code_image_url' => $paymentMethods[$key]['qr_code_image_url'] ?? null,
            ]);
        }

        return $paymentMethods;
    }
}
