<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name', 'parent_category_id'
    ];

    public function parentCategory()
    {
        return $this->belongsTo(BusinessCategory::class, 'parent_category_id');
    }

    public function childCategories()
    {
        return $this->hasMany(BusinessCategory::class, 'parent_category_id');
    }

    public function businessProfiles()
    {
        return $this->hasMany(BusinessProfile::class, 'business_category_id');
    }
}