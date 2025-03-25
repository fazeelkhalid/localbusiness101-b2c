<?php

namespace App\Http\Services;

use App\Enums\ErrorResponseEnum;
use App\Http\Mapper\DigitalCardMapper;
use App\Http\Requests\DigitalCard\CreateDigitalCardRequest;
use App\Http\Responses\DigitalCard\CreateDigitalCardResponses;
use App\Http\Responses\DigitalCard\GetDigitalCardResponses;
use App\Http\Utils\CustomUtils;
use App\Models\DigitalCard;
use App\Models\OfficeHour;
use App\Models\PaymentMethod;
use Exception;
use Illuminate\Support\Facades\DB;
class DigitalCardService
{
    public function createDigitalCard(CreateDigitalCardRequest $digitalCardCombinedRequest)
    {
        DB::beginTransaction();
        try {
            $digitalCardCombinedRequest = $digitalCardCombinedRequest->validated();
            $slug = DigitalCard::generateUniqueSlug($digitalCardCombinedRequest['business_name']);

            $headerImage = $digitalCardCombinedRequest['header_image'];
            $headerImageFilename = 'header_image-' . time() . '.' . $headerImage->getClientOriginalExtension();
            $digitalCardCombinedRequest['header_image_url'] = CustomUtils::uploadCardImage('/' . $slug, $headerImage, $headerImageFilename);

            $profileImage = $digitalCardCombinedRequest['profile_image'];
            $profileImageFilename = 'profile_image-' . time() . '.' . $profileImage->getClientOriginalExtension();
            $digitalCardCombinedRequest['profile_image_url'] = CustomUtils::uploadCardImage('/' . $slug, $profileImage, $profileImageFilename);

            $digitalCardCombinedRequest['slug'] = $slug;
            $digitalCard = DigitalCard::saveDigitalCard($digitalCardCombinedRequest);
            OfficeHour::saveOfficeHours($digitalCard->id, $digitalCardCombinedRequest['office_hours']);

            if (isset($digitalCardCombinedRequest['payment_methods'])) {
                $digitalCardCombinedRequest['payment_methods'] = PaymentMethod::savePaymentMethods($digitalCard->id, $digitalCardCombinedRequest['payment_methods'], $slug);
            }
            $digitalCardResponse = DigitalCardMapper::mapCreateDigitalCardRequestToResponse($digitalCardCombinedRequest);
            DB::commit();

            return new CreateDigitalCardResponses("Digital card created successfully", $digitalCardResponse, 201);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function getDigitalCardBySlug(string $slug){
        $digitalCard = DigitalCard::getDigitalCard($slug);
        if (!$digitalCard) {
            return ErrorResponseEnum::$DIGITAL_CARD_NOT_FOUND;
        }
        $digitalCard = DigitalCardMapper::mapDigitalCardDBToResponse($digitalCard);
        return new GetDigitalCardResponses($digitalCard);
    }
}
