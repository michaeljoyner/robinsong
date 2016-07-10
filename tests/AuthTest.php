<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 7/29/15
 * Time: 10:24 AM
 */

class AuthTest extends TestCase {

    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_disallows_guests_to_view_dashboard()
    {
        $this->visit('/admin')
            ->seePageIs('/admin/login');
    }

    /**
     * @test
     */
    public function it_logs_a_user_in()
    {
        $user = factory('App\User')->create(['password' => 'password']);

        $this->visit('/admin')
            ->seePageIs('/admin/login')
            ->type($user->email, 'email')
            ->type('password', 'password')
            ->press('Login')
            ->seePageIs('/admin/products/index');
    }

    /**
     * @test
     */
    public function it_logs_a_user_out()
    {
        $user = factory('App\User')->create();

        $this->actingAs($user)
            ->visit('admin/logout');

        $this->assertFalse(Auth::check(), 'user not logged out');
    }

}