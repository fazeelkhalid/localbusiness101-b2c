<?php

namespace App\Http\Mapper;

class ContactFormRequestMapper
{
    public static function mapContactFormRequestToResponse($validatedData)
    {
        return[
            "id" => $validatedData["id"],
            "phone_number" => $validatedData["phone_number"],
            "email" => $validatedData["email"],
            "subject" => $validatedData["subject"],
            "message" => $validatedData["message"]
        ];
    }

}