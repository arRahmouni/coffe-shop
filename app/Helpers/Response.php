<?php

use app\Enums\HttpStatusCode;
use Illuminate\Validation\Validator;

if(!function_exists('sendApiSuccessResponse'))
{
    function sendApiSuccessResponse(string $message = 'Data fetched successfully', array $data = [], int $code = HttpStatusCode::OK)
    {
        return response()->json([
            'success' => true,
            'code'    => $code,
            'message' => $message,
            'data'    => (object) $data,
            'errors'  => (object) [],
        ], $code);
    }
}

if(!function_exists('sendApiFailResponse'))
{
    function sendApiFailResponse(string $message = 'Something went wrong', array $errors = [], int $code = HttpStatusCode::BAD_REQUEST)
    {
        return response()->json([
            'success' => false,
            'code'    => $code,
            'message' => $message,
            'data'    => (object) [],
            'errors'  => (object) $errors,
        ], $code);
    }
}

if(!function_exists('sendSuccessInternalResponse'))
{
    function sendSuccessInternalResponse(string $message = null, array $data = [])
    {
        return [
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'errors'  => (object) [],
        ];
    }
}

if(!function_exists('sendFailInternalResponse'))
{
    function sendFailInternalResponse(string $message = null, array $errors = [])
    {
        return [
            'success' => false,
            'message' => $message,
            'data'    => (object) [],
            'errors'  => (object) $errors,
        ];
    }
}

if(!function_exists('sendValidationResponse'))
{
    function sendValidationResponse(Validator $validator)
    {
        return response()->json([
            'success' => false,
            'code'    => HttpStatusCode::UNPROCESSABLE_ENTITY,
            'message' => 'Validation failed',
            'data'    => (object) [],
            'errors'  => $validator->errors(),
        ], HttpStatusCode::UNPROCESSABLE_ENTITY);
    }
}
