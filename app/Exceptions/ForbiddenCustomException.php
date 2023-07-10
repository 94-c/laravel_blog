<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ForbiddenCustomException extends Exception
{
    protected $code = Response::HTTP_FORBIDDEN;

    public function __construct($message = null)
    {
        parent::__construct($message ?? 'This action is unauthorized.', $this->code);
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
