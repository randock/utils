<?php

declare(strict_types=1);

namespace Randock\Utils\Uuid;

use Randock\Utils\Uuid\Exception\UuidNotFoundException;

class UuidUtils
{
    /**
     * @param string $stringToSearch
     *
     * @throws UuidNotFoundException
     *
     * @return null|string
     */
    public static function getUuidFromString(string $stringToSearch): ?string
    {
        if (!preg_match('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/', $stringToSearch, $uuid)) {
            throw new UuidNotFoundException();
        }

        return $uuid[0];
    }
}
