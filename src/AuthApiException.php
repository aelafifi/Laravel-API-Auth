<?php

namespace ElMag\AuthApi;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthApiException extends HttpException
{

    public function __construct(int $statusCode, string $message = null)
    {
        parent::__construct($statusCode, $message);
    }
}