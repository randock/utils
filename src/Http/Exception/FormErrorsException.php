<?php

declare(strict_types=1);

namespace Randock\Utils\Http\Exception;

class FormErrorsException extends \Exception
{
    /**
     * @var array
     */
    private $errors;

    /**
     * FormErrorsException constructor.
     *
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        parent::__construct('randock.exception.form.errors');
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
