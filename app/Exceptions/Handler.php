<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

     /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            $message = $exception->getMessage() ? $exception->getMessage() : "End point doesn't exist";
            return formatResponse(404, $message);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            $message = $exception->getMessage() ? $exception->getMessage() : "This endpoint or method does not exist";
            return formatResponse(405, $message);
        }

        if ($exception instanceof ModelNotFoundException) {
            $message = $exception->getMessage() ? $exception->getMessage() : "Not Found";
            return formatResponse(404, $message);
        }

        if ($exception instanceof AuthenticationException) {
            $message = $exception->getMessage() ? $exception->getMessage() : "Invalid Token";
            return formatResponse(401, $message);
        }

        return parent::render($request, $exception);
    }

     /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return formatResponse($exception->status, $exception->getMessage(), false, $this->transformErrors($exception));
    }

    /**
     * Re-format validation error to return in apporitate format
     *
     * @param \Illuminate\Validation\ValidationException  $exception
     * @return array $errors
     */
    private function transformErrors(ValidationException $exception)
    {
        $errors = [];
        foreach ($exception->errors() as $field => $message) {
            $errors[] = [
               'field' => $field,
               'error_message' => $message[0],
            ];
        }
        return $errors;
    }
}
