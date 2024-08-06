<?php
namespace App\Http\Responses\Categories;

use Illuminate\Contracts\Support\Responsable;

class getCategoriesListResponse implements Responsable
{
    protected $categories;
    protected $status;

    public function __construct( $categories, int $status = 200)
    {
        $this->categories = $categories;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'categories' => $this->categories
        ], $this->status);
    }
}
