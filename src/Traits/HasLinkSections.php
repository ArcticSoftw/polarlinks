<?php

namespace ArcticSoftware\PolarLinks\Traits;

use ArcticSoftware\PolarLinks\Models\LinkSection;

trait HasLinkSections
{
    public function linkSections() {
        return $this->morphMany(LinkSection::class, 'author');
    }
}