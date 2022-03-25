<?php

namespace ArcticSoftware\PolarLinks;

class PolarLinkValidator
{
    private string $nameRegexpValidator = '/^[A-Za-z0-9_]+$/';
    private string $pathValidator = '/^(\/)?(((\w*|(\.\.\/))+\/)*)(\w+|\/|\w+\.\w+)$/';
    private array $loadOptions = [
        'format'    => [
            'collection',
            'array',
            'json'
        ]
    ];

    public function validName(string $pName): bool {
        if (preg_match($this->nameRegexpValidator, $pName))
            return true;

        return false;
    }

    public function validUrl(string $pUrl): bool {
        if (filter_var($pUrl, FILTER_VALIDATE_URL) || 
            preg_match($this->pathValidator,$pUrl)
        ) {
            return true;
        }

        return false;
    }

    public function checkLoadOptions(array $options): bool {

        return true;
    }
}