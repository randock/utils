<?php

declare(strict_types=1);

namespace Randock\Utils\Slug;

use Cocur\Slugify\Slugify;

class SlugUtils
{
    /**
     * @param string $slug
     *
     * @return string
     */
    public static function getSlug(string $slug): string
    {
        $slugify = new Slugify();
        $slugged = $slugify->slugify($slug);

        return $slugged;
    }
}
