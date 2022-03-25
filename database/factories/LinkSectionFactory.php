<?php

namespace ArcticSoftware\PolarLinks\Database\Factories;

use ArcticSoftware\PolarLinks\Models\LinkSection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LinkSectionFactory extends Factory
{
    protected $model = LinkSection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'name'  => 'section_' . Str::random(10),
        ];
    }
}