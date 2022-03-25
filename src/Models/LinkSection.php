<?php

namespace ArcticSoftware\PolarLinks\Models;

use ArcticSoftware\PolarLinks\Models\Link;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkSection extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory() {
        return \ArcticSoftware\PolarLinks\Database\Factories\LinkSectionFactory::new();
    }

    public function getTable() {
        return config('polarlinks.sections_table');
    }

    public function links() {
        return $this->hasMany(Link::class, 'linksections_id');
    }
}