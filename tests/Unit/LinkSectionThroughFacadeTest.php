<?php

namespace ArcticSoftware\PolarLinks\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use ArcticSoftware\PolarLinks\Facades\PolarSection;
use ArcticSoftware\PolarLinks\Facades\PolarLink;
use ArcticSoftware\PolarLinks\Tests\TestCase;
use ArcticSoftware\PolarLinks\Tests\User;
use ArcticSoftware\PolarLinks\Exceptions\PolarLinkSectionExceptions;
use ArcticSoftware\PolarLinks\Models\LinkSection;
use ArcticSoftware\PolarLinks\Models\Link;

class LinkSectionThroughFacadeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function test_fascade_linksection_creation() {
        LinkSection::factory()->count(2)->create();

        $this->assertDatabaseCount(config('polarlinks.sections_table'), 2);
    }

    /** @test */
    function test_fascade_linksection_retrieval() {
        $sections = LinkSection::factory()->count(2)->create();
        // Test if record is created by checking that linksection exists by getting link ORM database and matching id with 1. Using default load options
        $this->assertEquals(1, PolarSection::name($sections[0]->name)->get()->id, "Check if record exists and id of record is 1");
        $this->assertEquals(2, PolarSection::name($sections[1]->name)->get()->id, "Check if record exists and id of record is 2");
    }

    /** @test */
    function test_fascade_linksection_creation_when_duplicate() {
        PolarSection::name('a_section')
            ->create();

        $this->expectException(PolarLinkSectionExceptions::class);
        PolarSection::name('a_section')
            ->create();
    }

    /** @test */
    function test_linksection_creation_with_author_type() {
        $section = LinkSection::factory()->create([
            'name'          => 'a_name',
            'author_type'   => 'Fake\User'
        ]);

        $this->assertEquals('Fake\User', $section->author_type);
    }

    /** @test */
    function test_that_a_linksection_belongs_to_user() {
        $author = User::factory()->create();
        $author->linkSections()->create([
            'name'  => 'a_name'
        ]);

        $this->assertCount(1, LinkSection::all());
        $this->assertCount(1, $author->linkSections);

        tap($author->linkSections()->first(), function ($section) use ($author) {
            $this->assertEquals('a_name', $section->name);
            $this->assertTrue($section->author->is($author));
        });
    }

    /** @test */
    function test_link_to_linksection_relationship_association() {
        $sections = LinkSection::factory()->count(2)->create();
        $links = Link::factory()->count(10)->create();

        PolarSection::name($sections[0]->name)
            ->attach($links[0]);

        $this->assertEquals(1, PolarLink::name($links[0]->name)->get()->linksections_id);

        PolarSection::name($sections[0]->name)
            ->attach($links[1]);

        $this->assertEquals(1, PolarLink::name($links[1]->name)->get()->linksections_id);

        PolarSection::name($sections[1]->name)
            ->attach($links[8]);

        $this->assertEquals(2, PolarLink::name($links[8]->name)->get()->linksections_id);
    }

    /** @test */
    function test_what_happens_if_an_invalid_name_is_entered() {
        $this->expectException(PolarLinkSectionExceptions::class);
        PolarSection::name('A name')
            ->create();
    }

    /** @test */
    function test_de_associating_links_from_section() {
        $sections = LinkSection::factory()->count(2)->create();
        $links = Link::factory()->count(10)->create();

        PolarSection::name($sections[0]->name)
            ->attach($links[0]);
        $this->assertEquals(1, PolarLink::name($links[0]->name)->get()->linksections_id);
        PolarSection::name($sections[0]->name)
            ->empty();
        $this->assertNull(PolarLink::name($links[0]->name)->get()->linksections_id);

        PolarSection::name($sections[1]->name)
            ->attach($links[8]);
        $this->assertEquals(2, PolarLink::name($links[8]->name)->get()->linksections_id);
        PolarSection::name($sections[1]->name)
            ->empty();
        $this->assertNull(PolarLink::name($links[8]->name)->get()->linksections_id);
    }

    /** @test */
    function test_mass_assignment_and_mass_deleting_all_associated_links_in_section() {
        $section = LinkSection::factory()->create();
        $links = Link::factory()->count(2)->create();

        PolarSection::name($section->name)
            ->attachMore($links);

        $this->assertEquals(1, PolarLink::name($links[0]->name)->get()->linksections_id, "Check first record to see if it's associated");
        $this->assertEquals(1, PolarLink::name($links[1]->name)->get()->linksections_id, "Check last record to see if it's associated");

        PolarSection::name($section->name)
            ->purge();

        $this->assertDatabaseCount(config('polarlinks.links_table'), 0);
    }

    /** @test */
    function test_renaming_section() {
        $section = LinkSection::factory()->create();
        $sectionModel = PolarSection::name($section->name)
            ->get();

        $this->assertModelExists($sectionModel);

        PolarSection::name($section->name)
            ->newName('test_name');

        $sectionModel = PolarSection::name('test_name')
            ->get();

        $this->assertModelExists($sectionModel);
    }

    /** @test */
    function test_section_delete_no_purge_links() {
        $section = LinkSection::factory()->create();
        $links = Link::factory()->count(2)->create();

        PolarSection::name($section->name)
            ->attachMore($links);

        $this->assertEquals(1, PolarLink::name($links[0]->name)->get()->linksections_id, "Check first record to see if it's associated");
        $this->assertEquals(1, PolarLink::name($links[1]->name)->get()->linksections_id, "Check last record to see if it's associated");

        PolarSection::name($section->name)
            ->delete();

        $this->assertNull(PolarLink::name($links[0]->name)->get()->linksections_id);
        $this->assertNull(PolarLink::name($links[1]->name)->get()->linksections_id);
        $this->assertModelMissing($section);
    }

    /** @test */
    function test_section_delete_purge_links() {
        $section = LinkSection::factory()->create();
        $links = Link::factory()->count(2)->create();

        PolarSection::name($section->name)
            ->attachMore($links);

        $this->assertEquals(1, PolarLink::name($links[0]->name)->get()->linksections_id, "Check first record to see if it's associated");
        $this->assertEquals(1, PolarLink::name($links[1]->name)->get()->linksections_id, "Check last record to see if it's associated");

        $link1 = PolarLink::name($links[0]->name)->get();
        $link2 = PolarLink::name($links[1]->name)->get();
        PolarSection::name($section->name)
            ->delete(true);

        $this->assertModelMissing($link1);
        $this->assertModelMissing($link2);
        $this->assertModelMissing($section);
    }
}