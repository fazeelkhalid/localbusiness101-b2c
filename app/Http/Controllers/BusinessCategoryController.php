<?php

namespace App\Http\Controllers;

use App\Http\Mapper\BusinessCategoryMapper;
use App\Http\Responses\Categories\getCategoriesListResponse;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;

class BusinessCategoryController extends Controller
{
    public function getBusinessCategoriesList(){

        $businessCategories =  BusinessCategory::findAllCategoryAlongWithChild();
        $businessCategoryResponse = BusinessCategoryMapper::mapDBCategoriesIntoResponse($businessCategories);

        return new getCategoriesListResponse($businessCategoryResponse, 200);
    }

    public function getBusinessCategoriesNameList(){

        $businessCategories = BusinessCategory::getAllCategoryNames();
        $businessCategoryResponse = BusinessCategoryMapper::mapDBCategoriesNameListIntoResponse($businessCategories);
        return new getCategoriesListResponse($businessCategoryResponse, 200);
    }
}
