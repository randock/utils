<?php

declare(strict_types=1);

namespace Randock\Utils\Http\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException as BaseHttpException;

class HttpException extends BaseHttpException
{
    /**
     * @var string
     */
    private $body;

    public function __construct(int $statusCode, string $body = null, string $message = null, \Exception $previous = null, array $headers = [], int $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
