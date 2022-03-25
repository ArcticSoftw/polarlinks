<?php

namespace ArcticSoftware\PolarLinks;

use ArcticSoftware\PolarLinks\Models\Link;
use ArcticSoftware\PolarLinks\Exceptions\PolarLinkExceptions;
use ArcticSoftware\PolarLinks\PolarLinkValidator;

class PolarLink
{
    private Link $instance;
    private PolarLinkValidator $validator;

    public function __construct() {
        $this->instance = new Link;
        $this->validator = new PolarLinkValidator;
    }

    public function name($pName = null): PolarLink {
        $this->instance->name = $pName;
        return $this;
    }

    public function title(string $pTitle): PolarLink {
        $this->instance->title = $pTitle;
        return $this;
    }
    
    public function weight(int $pWeight): PolarLink {
        $this->instance->weight = $pWeight;
        return $this;
    }

    public function url(string $pUrl): PolarLink {
        $this->instance->url = $pUrl;
        return $this;
    }

    public function description(string $pDescription): PolarLink {
        $this->instance->description = $pDescription;
        return $this;
    }

    public function published(bool $pPublished): PolarLink {
        $this->instance->published = $pPublished;
        return $this;
    }

    public function testName(string $pName): bool {
        if ($this->validator->validName($pName))
            return true;
        return false;
    }

    public function testUrl(string $pUrl): bool {
        if ($this->validator->validUrl($pUrl))
            return true;
        return false;
    }

    public function checkIfExists(string $pName): bool {
        $record = Link::where('name', '=', $pName)
            ->first();

        if ($record === null)
            return false;

        return true;
    }

    public function get(): Link {
        if ($this->instance->name === null)
            throw PolarLinkExceptions::noNameIdentifierProvided();

        return Link::where('name', '=', $this->instance->name)
            ->first();
    }

    public function create(): Link {
        if ($this->instance->name === null)
            throw PolarLinkExceptions::noNameIdentifierProvided();
        elseif (!$this->validator->validName($this->instance->name))
            throw PolarLinkExceptions::nameIdentifierNotAlphaNumeric();
        elseif ($this->instance->url !== null && !$this->validator->validUrl($this->instance->url))
            throw PolarLinkExceptions::invalidLinkUrl();
        elseif ($this->checkIfExists($this->instance->name))
            throw PolarLinkExceptions::duplicateNameIdentifier();

        $this->instance->save();
        return $this->instance;
    }

    public function update(): Link {
        $newData = $this->instance;
        $this->instance = Link::where('name', '=', $newData->name)
            ->first();

        if ($this->instance === null)
            return $this->instance;
        elseif ($newData->name === null)
            throw PolarLinkExceptions::noNameIdentifierProvided();
        elseif (!$this->validator->validName($newData->name))
            throw PolarLinkExceptions::nameIdentifierNotAlphaNumeric();
        elseif ($newData->url !== null && !$this->validator->validUrl($this->instance->url))
            throw PolarLinkExceptions::invalidLinkUrl();

        if ($this->instance->title != $newData->title && $newData->title !== null)
            $this->instance->title = $newData->title;

        if ($this->instance->weight != $newData->weight && $newData->weight !== null)
            $this->instance->weight = $newData->weight;

        if ($this->instance->url != $newData->url && $newData->url !== null)
            $this->instance->url = $newData->url;

        if ($this->instance->description != $newData->description && $newData->description !== null)
            $this->instance->description = $newData->description;

        if ($this->instance->published != $newData->published && $newData->description !== null)
            $this->instance->published = $newData->published;

        $this->instance->save();
        return $this->instance;
    }

    public function delete(string $pName): void {
        $data = Link::where('name', '=', $pName)
            ->first();

        if ($data === null)
            return;

        $data->delete();
    }

    public function newName(string $pName): Link {
        $this->instance = Link::where('name', '=', $this->instance->name)
            ->first();

        if ($this->instance === null)
            return $this->instance;
        elseif (!$this->validator->validName($pName))
            throw PolarLinkExceptions::nameIdentifierNotAlphaNumeric();
        elseif ($this->checkIfExists($pName))
            throw PolarLinkExceptions::duplicateNameIdentifier();

        $this->instance->name = $pName;
        $this->instance->save();

        return $this->instance;
    }
}