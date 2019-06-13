<?php

namespace Randock\Utils\Http\Definition;

interface HeaderProviderInterface
{
    /**
     * Return an array with headers to add to the request
     *
     * @return array
     */
    public function getHeaders(): array;
}