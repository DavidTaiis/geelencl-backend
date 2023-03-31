<?php

namespace App\Transformers;

class ResponseTransformer
{

    /**
     * Transforms all responses to a common structure
     *
     * @param string $status
     * @param array $data
     * @param string $code
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function transformResponse($status, $data = [], $code = null, $message = null)
    {
        // Response arrays
        $success = null;
        $fail = null;
        $error = null;
        // Response codes
        $successCode = config('constants.response_codes.success');
        $failCode = config('constants.response_codes.validation_error');
        $errorCode = config('constants.response_codes.internal_server_error');
        // Response status
        $successStatus = config('constants.response_status.success');
        $failStatus = config('constants.response_status.validation_error');
        $errorStatus = config('constants.response_status.internal_server_error');
        
        switch($status) {
            case $successStatus: 
                $success = $data;
                $code = $code ?? $successCode;
                $message = $message ?? __('messages.success');
                break;
            case $failStatus: 
                $fail = $data;
                $code = $code ?? $failCode;
                $message = $message ?? __('messages.validation');
                break;
            case $errorStatus: 
                $error = $data;
                $code = $code ?? $errorCode;
                $message = $message ?? __('messages.error');
                break;
        }
        
        // Build response
        $response = [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'data' => $success,
            'warning' => $fail,
            'error' => $error
        ];

        return response()->json($response, $code);
    }
}