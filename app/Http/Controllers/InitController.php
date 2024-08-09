<?php

namespace App\Http\Controllers;

use App\Http\Mapper\BusinessCategoryMapper;
use App\Http\Requests\UserBusinessProfile\BusinessProfileFilterRequest;
use App\Http\Responses\Init\InitResponse;
use App\Http\Services\UserBusinessProfileService;
use App\Models\BusinessCategory;

class InitController extends Controller
{
    public function init(BusinessProfileFilterRequest $businessProfileFilterRequest)
    {
        $businessCategoriesNameList = BusinessCategory::getAllCategoryNames();
        $businessCategoriesNameList = BusinessCategoryMapper::mapDBCategoriesNameListIntoResponse($businessCategoriesNameList);

        $businessCategoriesHir = BusinessCategory::findAllCategoryAlongWithChild();
        $businessCategoriesHir = BusinessCategoryMapper::mapDBCategoriesIntoResponse($businessCategoriesHir);

        list($businessProfiles, $mappedBusinessProfiles) = UserBusinessProfileService::filterAndMapBusinessProfiles($businessProfileFilterRequest);

        return new InitResponse($businessCategoriesNameList, $businessCategoriesHir, $mappedBusinessProfiles);
    }
}
