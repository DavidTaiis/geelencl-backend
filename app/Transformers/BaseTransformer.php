<?php

namespace App\Transformers;

use Symfony\Component\HttpFoundation\Response;

class BaseTransformer
{

    /**
     * @param $code
     * @param $status
     * @param $message
     * @param $data
     * @param array $warning
     * @param array $error
     * @return array
     */
    public function transformToApiResponse($code, $status, $message, $data = null, $warning = null, $error = null)
    {

        return [
            'code' => $code,
            'status' => $status,
            'message' => Response::$statusTexts[$code] . ($message ? '. ' . $message : ''),
            'data' => $data,
            'warning' => $warning,
            'error' => $error
        ];

    }

    /**
     * @param \Exception $e
     * @return array
     */
    public function transformToApiResponseFromException(\Exception $e)
    {
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        $status = 'error';
        $message = Response::$statusTexts[$code] . $this->getMessageFromException($e);

        return $this->transformToApiResponse($code, $status, $message);

    }

    /**
     * @param \Exception $e
     * @return string
     */
    public function getMessageFromException(\Exception $e)
    {
        return config('app.env') !== 'production' ?
            $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() :
            __('error.repository_on_create');
    }

}