<?php

namespace App\Http\Responses\Init;

use Illuminate\Contracts\Support\Responsable;

class InitResponse implements Responsable
{
    protected $init;
    protected $status;
    protected $businessCategoriesNameList;
    protected $businessCategoriesHir;
    protected $businessProfiles;

    public function __construct($businessCategoriesNameList, $businessCategoriesHir, $businessProfiles, int $status = 200)
    {
        $this->businessCategoriesNameList = $businessCategoriesNameList;
        $this->businessCategoriesHir = $businessCategoriesHir;
        $this->businessProfiles = $businessProfiles;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'categories_name_list' => $this->businessCategoriesNameList,
            'categories_names' => $this->businessCategoriesHir,
            'business_profiles' => $this->businessProfiles,
        ], $this->status);
    }
}
