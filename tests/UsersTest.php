<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersTest extends TestCase {

    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_registers_a_user()
    {
        $currentUser = factory('App\User')->create();
        $userDetails = factory('App\User')->make();

        $userDetailsArray = $userDetails->toArray();
        $userDetailsArray['password'] = 'password';
        $userDetailsArray['password_confirmation'] = 'password';


        $this->actingAs($currentUser)
            ->visit('/admin/register')
            ->submitForm('Register', $userDetailsArray);

        $this->seeInDatabase('users', $userDetails->toArray());
    }

    /**
     * @test
     */
    public function it_wont_register_with_a_duplicate_email()
    {
        $currentUser = factory('App\User')->create();

        $validUser = factory('App\User')->create(['name' => 'Good Joe', 'email' => 'joe@example.com']);

        $userDetails = [
            'name' => 'Bad Joe',
            'email' => 'joe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->actingAs($currentUser)
            ->visit('/admin/register')
            ->submitForm('Register', $userDetails)
            ->see('that email has already been taken');

        $this->seeInDatabase('users', $validUser->toArray());
        $this->notSeeInDatabase('users', ['name' => 'Bad Joe', 'email' => 'joe@example.com']);
    }

    /**
     * @test
     */
    public function a_user_can_be_edited()
    {
        $currentUser = factory('App\User')->create();

        $validUser = factory('App\User')->create(['name' => 'Good Joe', 'email' => 'joe@example.com']);

        $this->actingAs($currentUser)
            ->visit('/admin/users/'.$validUser->id.'/edit')
            ->type('Bad Joe', 'name')
            ->type('badjoe@example.com', 'email')
            ->press('Save');

        $this->seeInDatabase('users', ['id' => $validUser->id, 'name' => 'Bad Joe', 'email' => 'badjoe@example.com']);
        $this->notSeeInDatabase('users', ['name' => 'Good Joe', 'email' => 'joe@example.com']);
    }

    /**
     * @test
     */
    public function it_deletes_a_user()
    {
        $user1 = factory('App\User')->create();
        $user2 = factory('App\User')->create();

        $this->withoutMiddleware();

        $this->actingAs($user1);

        $this->call('DELETE', '/admin/users/'.$user2->id);

        $this->notSeeInDatabase('users', $user2->toArray());
    }

    /**
     * @test
     */
    public function it_wont_delete_the_final_user()
    {
        $user1 = factory('App\User')->create();

        $this->withoutMiddleware();

        $this->actingAs($user1);

        $this->call('DELETE', '/admin/users/'.$user1->id);

        $this->seeInDatabase('users', $user1->toArray());
    }
}