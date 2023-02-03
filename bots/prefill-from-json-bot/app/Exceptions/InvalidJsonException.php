<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class InvalidJsonException extends \RuntimeException implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function toResponse($request)
    {
        return new JsonResponse(
            [
                'message' => "The attached JSON file could not be read, it contains an invalid structure"
            ],
            422
        );
    }
}
