<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 7/29/15
 * Time: 10:17 AM
 */
class PagesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_shows_the_homepage()
    {
        $this->visit('/')
            ->seePageIs('/');
    }

    /**
     * @test
     */
    public function it_shows_dashboard()
    {
        $currentUser = factory('App\User')->create();

        $this->actingAs($currentUser)
            ->visit('/admin')
            ->seePageIs('/admin');
    }

}