<?php

namespace ArcticSoftware\PolarLinks\Exceptions;

use Exception;

class PolarLinkSectionExceptions extends Exception
{
    public static function noNameIdentifierProvided(): self {
        return new static ('Can not perform query on a null string (String: Link section name)');
    }

    public static function nameIdentifierNotAlphaNumeric(): self {
        return new static ('Can not perform query on a string that is not alpha numeric (String: Link section name)');
    }

    public static function duplicateNameIdentifier(): self {
        return new static ('Can not perform query on a duplicate identifier (Link section name)');
    }

    public static function noResultsReturned(): self {
        return new static ('No results in collection');
    }
}