<?php

namespace App\Http\Controllers;

use App\Enums\ErrorResponseEnum;
use App\Http\Mapper\DigitalCardMapper;
use App\Http\Requests\DigitalCard\CreateDigitalCardRequest;
use App\Http\Responses\DigitalCard\CreateDigitalCardResponses;
use App\Http\Responses\DigitalCard\GetDigitalCardResponses;
use App\Http\Services\AcquirerService;
use App\Http\Services\DigitalCardService;
use App\Http\Services\UserBusinessProfileService;
use App\Http\Utils\CustomUtils;
use App\Models\DigitalCard;
use App\Models\OfficeHour;
use App\Models\PaymentMethod;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class DigitalCardController extends Controller
{

    protected AcquirerService $acquirerService;
    protected DigitalCardService $digitalCardService;

    public function __construct(AcquirerService $acquirerService, DigitalCardService $digitalCardService)
    {
        $this->acquirerService = $acquirerService;
        $this->digitalCardService = $digitalCardService;
    }

    public function createDigitalCard(CreateDigitalCardRequest $digitalCardCombinedRequest)
    {
//        $this->acquirerService->hasAuthorityOrThrowException("createDigitalCard");
        return $this->digitalCardService->createDigitalCard($digitalCardCombinedRequest);
    }
    public function getDigitalCardBySlug(string $slug){
        return $this->digitalCardService->getDigitalCardBySlug($slug);
    }
}
