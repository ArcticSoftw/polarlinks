<?php

namespace ArcticSoftware\PolarLinks;

use ArcticSoftware\PolarLinks\Models\LinkSection;
use ArcticSoftware\PolarLinks\Models\Link;
use ArcticSoftware\PolarLinks\Exceptions\PolarLinkSectionExceptions;
use ArcticSoftware\PolarLinks\PolarLinkValidator;
use Illuminate\Database\Eloquent\Collection;

class PolarSection
{
    private LinkSection $instance;
    private PolarLinkValidator $validator;
    private array $options;

    private function initiate(): void {
        $this->instance = LinkSection::where('name', '=', $this->instance->name)
            ->first();
    }

    private function checkValidName(?string $pName = null): void {
        if ($pName === null)
            $pName = $this->instance->name;

        if ($this->instance->name === null)
            throw PolarLinkSectionExceptions::noNameIdentifierProvided();
        elseif (!$this->validator->validName($pName))
            throw PolarLinkSectionExceptions::nameIdentifierNotAlphaNumeric();
    }

    public function __construct() {
        $this->instance = new LinkSection;
        $this->validator = new PolarLinkValidator;
    }

    public function name(string $pName): PolarSection {
        $this->instance->name = $pName;
        return $this;
    }

    public function load(array $options): PolarSection {
        $this->options = $options;
        return $this;
    }

    public function attach(Link $pLink): void {
        $this->checkValidName();
        $this->initiate();

        $pLink->linkSection()->associate($this->instance);
        $pLink->save();
    }

    public function attachMore(Collection $pLinks): void {
        $this->checkValidName();
        $this->initiate();

        foreach ($pLinks as $link) {
            $link->linksections_id = $this->instance->id;
            $link->save();
        }
    }

    public function checkIfExists(string $pName): bool {
        $section = LinkSection::where('name', '=', $pName)
            ->first();

        if ($section === null)
            return false;

        return true;
    }

    public function get(): LinkSection {
        $this->checkValidName();
        if (!$this->checkIfExists($this->instance->name))
            throw PolarLinkSectionExceptions::noResultsReturned();

        $this->initiate();

        if (!empty($this->options)) {
            if ($this->options['format'] == 'collection')
                return $this->instance->links()->get();
            elseif ($this->options['format'] == 'array')
                return $this->instance->links()->get()->toArray();
            elseif ($this->options['format'] == 'json')
                return $this->instance->links()->get()->toJson();
        }

        return $this->instance;
    }

    public function create(): LinkSection {
        $this->checkValidName();

        if ($this->checkIfExists($this->instance->name))
            throw PolarLinkSectionExceptions::duplicateNameIdentifier();

        $this->instance->save();
        return $this->instance;
    }

    public function delete(bool $purgeLinks = false): void {
        $this->checkValidName();
        $this->initiate();

        if ($purgeLinks) {
            Link::where('linksections_id', '=', $this->instance->id)
                ->delete();
        } else {
            Link::where('linksections_id', '=', $this->instance->id)
                ->update(['linksections_id' => null]);
        }

        $this->instance->delete();
    }

    public function empty(): void {
        $this->checkValidName();

        $this->initiate();
        Link::where('linksections_id', '=', $this->instance->id)
            ->update(['linksections_id' => null]);
    }

    public function purge(): void {
        $this->checkValidName();

        $this->initiate();
        Link::where('linksections_id', '=', $this->instance->id)
            ->delete();
    }

    public function newName(string $pName): void {
        $this->checkValidName();
        $this->checkValidName($pName);

        if ($pName === null)
            throw PolarLinkSectionExceptions::noNameIdentifierProvided();
        elseif ($this->checkIfExists($pName))
            throw PolarLinkSectionExceptions::duplicateNameIdentifier();
        
        $this->initiate();
        $this->instance->name = $pName;
        $this->instance->save();
    }
}