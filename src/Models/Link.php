<?php

namespace ArcticSoftware\PolarLinks\Models;

use ArcticSoftware\PolarLinks\Models\LinkSection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory() {
        return \ArcticSoftware\PolarLinks\Database\Factories\LinkFactory::new();
    }

    public function getTable() {
        return config('polarlinks.links_table');
    }

    public function linkSection() {
        return $this->belongsTo(LinkSection::class, 'linksections_id');
    }
}