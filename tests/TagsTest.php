<?php
use App\Stock\Tag;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 12/21/15
 * Time: 11:09 AM
 */
class TagsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function tags_can_be_created()
    {
        $tag = Tag::makeTag(['name' => 'classic']);

        $this->assertEquals('classic', $tag->name);
    }

    /**
     * @test
     */
    public function make_tag_will_return_existing_tag_if_name_already_exists()
    {
        $firstTag = Tag::makeTag(['name' => 'classic']);

        $nextTag = Tag::makeTag(['name' => 'classic']);

        $this->assertEquals($firstTag->id, $nextTag->id);
    }

    /**
     * @test
     */
    public function tags_are_case_insensitive_and_stored_as_lowercase()
    {
        $firstTag = Tag::makeTag(['name' => 'classic']);

        $nextTag = Tag::makeTag(['name' => 'ClaSsIc']);

        $this->assertEquals($firstTag->id, $nextTag->id);
    }
}