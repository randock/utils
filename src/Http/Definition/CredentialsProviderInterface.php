<?php

declare(strict_types=1);

namespace Randock\Utils\Http\Definition;

interface CredentialsProviderInterface
{
    /**
     * Return an array with username, password (without keys) to add to ['auth'] options
     *
     * @return array|null
     */
    public function getCredentials(): array;

}
