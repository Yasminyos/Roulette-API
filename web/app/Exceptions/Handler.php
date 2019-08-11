<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

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
     * @param  Exception  $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }
    
    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Exception  $exception
     * @return JsonResponse|Response|SymfonyResponse
     */
    public function render($request, Exception $exception)
    {
//        if (!($exception instanceof ValidationException)) {
//            $response = [];
//
//            $response['exception'] = get_class($exception);
//            $response['message'] = $exception->getMessage();
//
//            if (config('app.debug')) {
//                $response['trace'] = $exception->getTrace();
//            }
//
//            $status = 400;
//
//            if ($this->isHttpException($exception)) {
//                $status = $exception->getStatusCode();
//            }
//
//            return response()->json($response, $status);
//        }
        
        return parent::render($request, $exception);
    }
}
