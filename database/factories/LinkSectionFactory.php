<?php

namespace ArcticSoftware\PolarLinks\Database\Factories;

use ArcticSoftware\PolarLinks\Models\LinkSection;
use ArcticSoftware\PolarLinks\Tests\User;
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
        $author = User::factory()->create();

        return [
            'name'          => 'section_' . Str::random(10),
            'author_id'     => $author->id,
            'author_type'   => get_class($author)
        ];
    }
}