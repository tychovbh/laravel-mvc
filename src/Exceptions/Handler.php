<?php

namespace Tychovbh\Mvc\Exceptions;


use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends AbstractHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            emergency('Production error!', $exception);
        }
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
        $message = $exception->getMessage();
        $status = 500;
        if ($exception instanceof NotFoundHttpException) {
            $message = $exception->getMessage() === '' ? 'Sorry, the page you are looking for could not be found.' : $message;
            $status = 404;
        }

        if ($exception instanceof ModelNotFoundException) {
            $status = 404;
        }

        if ($exception instanceof HttpResponseException) {
            return parent::render($request, $exception);
        }

        if (method_exists($exception, 'getStatusCode')) {
            $status = $exception->getStatusCode();
        }

        if ($status === 500) {
            $message = message('server.error');
        }

        return response()->json(['message' => $message], $status);
    }
}
