<?php

namespace ArcticSoftware\PolarLinks\Facades;

use Illuminate\Support\Facades\Facade;

class PolarLink extends Facade
{
    protected static function getFacadeAccessor() {
        return 'polarlink';
    }
}