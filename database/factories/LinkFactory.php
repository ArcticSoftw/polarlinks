<?php

namespace ArcticSoftware\PolarLinks\Database\Factories;

use ArcticSoftware\PolarLinks\Models\Link;
use ArcticSoftware\PolarLinks\Models\LinkSection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LinkFactory extends Factory
{
    protected $model = Link::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'title'         => $this->faker->words(4, true),
            'name'          => $this->faker->words(1, true) . '_' . Str::random(5),
            'weight'        => 1,
            'url'           => $this->faker->url,
            'description'   => $this->faker->words(120, true)
        ];
    }
}