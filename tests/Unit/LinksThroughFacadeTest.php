<?php

namespace ArcticSoftware\PolarLinks\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use ArcticSoftware\PolarLinks\Facades\PolarLink;
use ArcticSoftware\PolarLinks\Tests\TestCase;
use ArcticSoftware\PolarLinks\Exceptions\PolarLinkExceptions;

class LinksThroughFacadeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function test_fascade_link_creation_and_link_get() {
        PolarLink::name('a_name')
            ->title('A title')
            ->weight(8)
            ->url('http://www.arcticsoftware.no')
            ->description('A longer description of the link')
            ->published(true)
            ->create();
        
        // Test if record is created by checking that link exists by getting link ORM database and matching id with 1
        $this->assertEquals(1, PolarLink::name('a_name')->get()->id, "Check if record exists and id of record is 1");
        // Check all model parameters
        $this->assertEquals('A title', PolarLink::name('a_name')->get()->title, "Check if title of record is 'A title'");
        $this->assertEquals(8, PolarLink::name('a_name')->get()->weight, "Check if weight value of record is 8");
        $this->assertEquals('http://www.arcticsoftware.no', PolarLink::name('a_name')->get()->url, "Check if URL value of record is 'http://www.arcticsoftware.no'");
        $this->assertEquals('A longer description of the link', PolarLink::name('a_name')->get()->description, "Check if description value of record is 'A longer description of the link'");
        $this->assertEquals(1, PolarLink::name('a_name')->get()->published, "Check if published value of record is 1 (and thus true)");
    }

    /** @test */
    function test_fascade_link_update_routines() {
        PolarLink::name('a_name')
            ->title('A title')
            ->weight(8)
            ->url('http://www.arcticsoftware.no')
            ->description('A longer description of the link')
            ->published(true)
            ->create();

        PolarLink::name('a_name')
            ->title('Another title')
            ->weight(4)
            ->url('http://www.vg.no')
            ->description('A completely different description')
            ->published(false)
            ->update();

        // Test if record is created by checking that link exists by getting link ORM database and matching id with 1
        $this->assertEquals(1, PolarLink::name('a_name')->get()->id, "Check if record exists and id of record is 1");
        // Check all model parameters
        $this->assertEquals('Another title', PolarLink::name('a_name')->get()->title, "Check if title of record is 'Another title'");
        $this->assertEquals(4, PolarLink::name('a_name')->get()->weight, "Check if weight value of record is 4");
        $this->assertEquals('http://www.vg.no', PolarLink::name('a_name')->get()->url, "Check if URL value of record is 'http://www.vg.no'");
        $this->assertEquals('A completely different description', PolarLink::name('a_name')->get()->description, "Check if description value of record is 'A completely different description'");
        $this->assertEquals(0, PolarLink::name('a_name')->get()->published, "Check if published value of record is 0 (and thus false)");
    }

    /** @test */
    function test_fascade_link_creation_when_duplicate_exists_in_database() {
        PolarLink::name('a_name')
            ->create();

        // Test for exception thrown if duplicate
        $this->expectException(PolarLinkExceptions::class);
        PolarLink::name('a_name')
            ->create();
    }

    /** @test */
    function test_fascade_link_update_single_routine() {
        PolarLink::name('a_name')
            ->title('A title')
            ->weight(8)
            ->url('http://www.arcticsoftware.no')
            ->description('A longer description of the link')
            ->published(true)
            ->create();

        PolarLink::name('a_name')
            ->title('Another title')
            ->update();

        // Test if record is created by checking that link exists by getting link ORM database and matching id with 1
        $this->assertEquals(1, PolarLink::name('a_name')->get()->id, "Check if record exists and id of record is 1");
        // Check all model parameters
        $this->assertEquals('Another title', PolarLink::name('a_name')->get()->title, "Check if title of record is 'Another title'");
        $this->assertEquals(8, PolarLink::name('a_name')->get()->weight, "Check if weight value of record is 8");
        $this->assertEquals('http://www.arcticsoftware.no', PolarLink::name('a_name')->get()->url, "Check if URL value of record is 'http://www.arcticsoftware.no'");
        $this->assertEquals('A longer description of the link', PolarLink::name('a_name')->get()->description, "Check if description value of record is 'A longer description of the link'");
        $this->assertEquals(1, PolarLink::name('a_name')->get()->published, "Check if published value of record is 1 (and thus true)");
    }

    /** @test */
    function test_fascade_link_delete_singleton_routine() {
        PolarLink::name('a_name')
            ->title('A title')
            ->weight(8)
            ->url('http://www.arcticsoftware.no')
            ->description('A longer description of the link')
            ->published(true)
            ->create();

        // Test if record is created by checking that link exists by getting link ORM database and matching id with 1
        $this->assertTrue(PolarLink::checkIfExists('a_name'), "Check if record exists and id of record is 1");
        PolarLink::delete('a_name');
        // Check that the record is empty and thus deleted
        $this->assertFalse(PolarLink::checkIfExists('a_name'), "Check if record is deleted");
    }

    /** @test */
    function test_fascade_link_class_validators() {
        // Test alphanumeric and underscore name validator
        $this->assertTrue(PolarLink::testName('a_name'));
        $this->assertTrue(PolarLink::testName('aname_'));
        $this->assertTrue(PolarLink::testName('a_n4m3_'));
        $this->assertFalse(PolarLink::testName('A name'));
        $this->assertFalse(PolarLink::testName(''));
        $this->assertFalse(PolarLink::testName(' '));

        // Test URL validator
        $this->assertTrue(PolarLink::testUrl('https://www.arcticsoftware.no'));
        $this->assertTrue(PolarLink::testUrl('http://localhost'));
        $this->assertTrue(PolarLink::testUrl('http://vg.no/path/to/stuff.php'));
        $this->assertTrue(PolarLink::testUrl('/path/to/the/thing.html'));
        $this->assertTrue(PolarLink::testUrl('/path/tothing'));
        $this->assertTrue(PolarLink::testUrl('path/tothing'));
        $this->assertTrue(PolarLink::testUrl('path/to/file.png'));
        $this->assertTrue(PolarLink::testUrl('aurl'));
        $this->assertFalse(PolarLink::testUrl('invalid url/test'));
        $this->assertFalse(PolarLink::testUrl('http://??d#"$"$"'));
    }

    /** @test */
    function test_fascade_link_exceptions() {
        // Test for exception thrown if no name is given
        $this->expectException(PolarLinkExceptions::class);
        PolarLink::name()->create();
    }

    /** @test */
    function test_fascade_link_exception_for_invalid_name() {
        // Test for exception thrown if name is not alphanumeric
        $this->expectException(PolarLinkExceptions::class);
        PolarLink::name('A name with spaces')->create();
    }

    /** @test */
    function test_fascade_link_exception_for_invalid_url() {
        // Test for exception thrown if URL is invalid
        $this->expectException(PolarLinkExceptions::class);
        PolarLink::name('a_name')->url('invalid url/test')->create();
    }

    /** @test */
    function test_fascade_link_renaming() {
        PolarLink::name('a_name')
            ->create();

        // Test if record is created by checking that link exists by getting link ORM database and matching id with 1
        $this->assertEquals(1, PolarLink::name('a_name')->get()->id, "Check if record exists and id of record is 1");

        PolarLink::name('a_name')
            ->newName('b_name');

        // Test if record is renamed by checking that link exists by getting link ORM database and matching id with 1
        $this->assertEquals(1, PolarLink::get('b_name')->id, "Check if record exists and id of record is 1");
    }

    /** @test */
    function test_fascade_link_exception_for_invalid_name_when_renaming() {
        PolarLink::name('a_name')
            ->create();
        // Test for exception thrown if URL is invalid
        $this->expectException(PolarLinkExceptions::class);
        PolarLink::name('a_name')
            ->newName('b name!!!@');
    }
}