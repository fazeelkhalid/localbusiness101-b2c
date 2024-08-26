<?php
namespace App\Http\Responses\Categories;

use Illuminate\Contracts\Support\Responsable;

class CreateCategoryResponse implements Responsable
{

    protected $categories;
    protected $message;
    protected $status;

    public function __construct($message, $categories, int $status = 200, )
    {
        $this->message = $message;
        $this->categories = $categories;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
            'category' => $this->categories
        ], $this->status);
    }
}
