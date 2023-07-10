<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class NotFoundCustomException extends Exception
{
    protected $code = Response::HTTP_NOT_FOUND;

    public function __construct($message = null)
    {
        parent::__construct($message ?? 'Not Found.', $this->code);
    }

    public function render()
    {
        return response()->json([
            'data' => [
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
            ]
        ], $this->code);
    }
}
