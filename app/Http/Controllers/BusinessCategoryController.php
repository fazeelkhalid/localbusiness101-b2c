<?php

namespace App\Http\Controllers;

use App\Http\Mapper\BusinessCategoryMapper;
use App\Http\Requests\BusinessProfileCategory\BusinessProfileCategoryRequest;
use App\Http\Responses\Categories\CreateCategoryResponse;
use App\Http\Responses\Categories\getCategoriesListResponse;
use App\Http\Services\AcquirerService;
use App\Models\BusinessCategory;

class BusinessCategoryController extends Controller
{
    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }

    public function getBusinessCategoriesList()
    {

        $businessCategories = BusinessCategory::findAllCategoryAlongWithChild();
        $businessCategoryResponse = BusinessCategoryMapper::mapDBCategoriesIntoResponse($businessCategories);

        return new getCategoriesListResponse($businessCategoryResponse, 200);
    }

    public function getBusinessCategoriesNameList()
    {

        $businessCategories = BusinessCategory::getAllCategoryNames();
        $businessCategoryResponse = BusinessCategoryMapper::mapDBCategoriesNameListIntoResponse($businessCategories);
        return new getCategoriesListResponse($businessCategoryResponse, 200);
    }

    public function CreateCategory(BusinessProfileCategoryRequest $businessProfileCategoryRequest)
    {
        $this->acquirerService->hasAuthorityOrThrowException("CreateCategory");
        $validatedData = $businessProfileCategoryRequest->validated();
        $parentCategoryId = null;

        if (!empty($validatedData['parent_category_name'])) {
            $parentCategory = BusinessCategory::where('category_name', $validatedData['parent_category_name'])->first();
            $parentCategoryId = $parentCategory->id;
        }

        $category = new BusinessCategory();
        $category->category_name = $validatedData['category_name'];
        $category->parent_category_id = $parentCategoryId;
        $category->save();

        return new CreateCategoryResponse("Category created successfully.", $category->category_name, 200);
    }

}
