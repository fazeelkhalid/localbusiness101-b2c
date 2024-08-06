<?php

namespace App\Http\Mapper;

class BusinessCategoryMapper
{
    public static function mapDBCategoriesIntoResponse($categories)
    {
        return $categories->map(function ($category) {
            return [
//                'id' => $category->id,
                'name' => $category->category_name,
                'children' => BusinessCategoryMapper::mapDBCategoriesIntoResponse($category->childCategories)
            ];
        });
    }
}
