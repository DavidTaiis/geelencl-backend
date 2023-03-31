<?php

namespace App\Validators;

use App\Transformers\BaseTransformer;
use App\Transformers\ErrorTransformer;
use App\Transformers\ResponseTransformer;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BaseValidator
{

    /**
     * @var BaseTransformer
     */
    private $baseTransformer;

    /**
     * BaseValidator constructor.
     * @param BaseTransformer $baseTransformer
     */
    public function __construct(BaseTransformer $baseTransformer)
    {
        $this->baseTransformer = $baseTransformer;
    }

    /**
     * @param $errors
     * @return array
     */
    public function formatErrors($errors)
    {
        $warnings = array();
        $i = 0;

        foreach ($errors as $key => $value) {
            $error['code'] = null;
            $error['field'] = $key;
            $error['value'] = $value[0];
            $warnings[$i++] = $error;
        }

        return $this->baseTransformer->transformToApiResponse(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            "fail",
            "",
            null,
            $warnings
        );
    }

    /**
     * Validate request and transform the error response
     *
     * @param array $request
     * @param array $rules
     * @param array $messages
     * @return bool|HttpResponseException
     */
    public function validate(array $request, array $rules, array $messages = null)
    {
        $validator = $messages === null ? Validator::make($request, $rules) :
            Validator::make($request, $rules, $messages);

        if ($validator->fails()) {
            $status = config('constants.response_status.validation_error');
            $errors = ErrorTransformer::transformValidationErrors($validator->errors());
            $response = ResponseTransformer::transformResponse($status, $errors);
            throw new HttpResponseException($response);
        }

        return true;
    }

    public function validateNoSavedEntity(array $request)
    {
        $status = config('constants.response_status.validation_error');
        $errors = [];
        $response = ResponseTransformer::transformResponse($status, $errors, null, "Error al integrar con strava");
        throw new HttpResponseException($response);
    }

    public function validateStravaIntegrationExist(array $request)
    {
        $status = config('constants.response_status.validation_error');
        $errors = [];
        $response = ResponseTransformer::transformResponse($status, $errors, null, "La cuenta de STRAVA ya esta asociada a una cuenta de LANZA, intenta con otra cuenta.");
        throw new HttpResponseException($response);
    }
}