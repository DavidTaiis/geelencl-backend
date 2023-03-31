<?php

namespace App\Exceptions;

use App\Http\Transformers\ErrorTransformer;
use App\Http\Transformers\ResponseTransformer;
use Illuminate\Validation\ValidationException;

class CustomValidationException extends ValidationException
{
    /**
     * Render an exception into an Hconstants.phpTTP response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        // This will replace the 404 response with a JSON response
        $status = config('constants.response_status.validation_error');
        
        $errors = ErrorTransformer::transformValidationErrors($this->validator->errors());

        return ResponseTransformer::transformResponse($status, $errors);
    }
}