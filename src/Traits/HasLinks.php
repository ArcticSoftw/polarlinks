<?php

namespace ArcticSoftware\PolarLinks\Traits;

use ArcticSoftware\PolarLinks\Models\Link;

trait HasLinks
{
    public function links() {
        return $this->morphMany(Link::class, 'author');
    }
}