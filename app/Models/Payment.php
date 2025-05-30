<?php

namespace App\Models;

use http\Env\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'amount',
        'description',
        'seen_count',
        'is_paid',
        'response',
        'payment_id',
        'payment_link',
        'paypro_id',
        'payment_method',
        'paypro_payment_status',
        'client_email',
        'client_name',
        'client_phone_number',
    ];


    public static function createPayment(mixed $validatedData): Payment
    {
        $paymentId = self::generateUniquePaymentId();

        return self::create([
            'payment_id' => $paymentId,
            'amount' => $validatedData['amount'],
            'payment_method' => $validatedData['method'],
            'description' => $validatedData['description'],
            'currency' => $validatedData['currency'],
            'client_email' => $validatedData['client_email'],
            'client_name' => $validatedData['client_name'],
            'client_phone_number' => $validatedData['client_phone_number'] ?? null,
        ]);
    }

    public static function generateUniquePaymentId()
    {
        do {
            $paymentId = strtoupper(Str::random(6));
        } while (self::where('payment_id', $paymentId)->exists());

        return $paymentId;
    }

    public static function getAndSetPaymentIsSeen($payment_id)
    {
        $payment = Payment::where('payment_id', $payment_id)->where('is_paid', false)->firstOrFail();
        $payment->increment('seen_count');
        $payment->save();
        return $payment;
    }

    public static function UpdatePayProPaymentInfo($payProPayment, $payment_link, Payment $payment): void
    {
        $payment->paypro_id = $payProPayment;
        $payment->paypro_payment_status = 'UNPAID';
        $payment->payment_link = $payment_link;

        $payment->save();
    }



}
