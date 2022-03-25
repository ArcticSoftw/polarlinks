<?php

namespace ArcticSoftware\PolarLinks\Exceptions;

use Exception;

class PolarLinkExceptions extends Exception
{
    public static function noNameIdentifierProvided(): self {
        return new static ('Can not perform query on a null string (String: Link name)');
    }

    public static function nameIdentifierNotAlphaNumeric(): self {
        return new static ('Can not perform query on a string that is not alpha numeric (String: Link name)');
    }

    public static function invalidLinkUrl(): self {
        return new static ('Can not perform query with an invalid URL');
    }

    public static function duplicateNameIdentifier(): self {
        return new static ('Can not perform query on a duplicate identifier (Link name)');
    }

    public static function noResultsReturned(): self {
        return new static ('No results in collection');
    }
}