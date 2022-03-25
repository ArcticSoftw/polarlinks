<?php

namespace ArcticSoftware\PolarLinks\Facades;

use Illuminate\Support\Facades\Facade;

class PolarSection extends Facade
{
    protected static function getFacadeAccessor() {
        return 'polarsection';
    }
}