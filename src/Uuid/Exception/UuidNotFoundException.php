<?php

declare(strict_types=1);

namespace Randock\Utils\Uuid\Exception;

class UuidNotFoundException extends \Exception
{
    /**
     * UuidNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('randock.consumerui.exception.common.uuid_not_found');
    }
}
