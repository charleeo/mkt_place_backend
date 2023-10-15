<?php

namespace App\Http\Configuration;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;


final class ValidationUtils
{

    public const UNAUTHORIZED_ACCESS_TO_TAXPAYER_DATA = 'You do not have the permission to the selected taxpayer.';

    /**
     * 
     * check if the tax payer type is a valid type
     */
    public static function isValidTaxpayerType($value)
    {

        $taxpayerTypes = config('enums.taxpayer_types');
        for ($i = 0; $i < count($taxpayerTypes); $i++) {
            if ($taxpayerTypes[$i] === $value) {
                return true;
            }
        }

        return false;
    }

    public static function startsWithNumber($str)
    {
        return preg_match('/^\d/', $str) === 1;
    }

    public function validationErrorResponse(ValidationException $exception, Request $request)
    {
        $errors = $this->formatErrorBlock($exception->validator);

        // package the response data
        $res = AppHttpUtils::responseStructure("Validation not passed.", false, $errors);

        // write to log
        write_log(Logger::getLogData($request, $res, 'validation error'));

        // return response
        return response()->json($res, Response::HTTP_BAD_REQUEST);
    }

    /**
     * 
     * format and return validation messages
     */
    private function formatErrorBlock($validator)
    {
        // extract errors into array
        $errors = $validator->errors()->toArray();
        $errorResponse = [];

        // loop throtugh the errors and save them in array
        foreach ($errors as $field => $message) {
            $errorField = ['field' => $field];

            foreach ($message as $key => $msg) {
                if ($key) {
                    $errorField['message' . $key] = $msg;
                } else {
                    $errorField['message'] = $msg;
                }
            }

            $errorResponse[] = $errorField;
        }

        return $errorResponse;
    }
}
