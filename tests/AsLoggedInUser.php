<?php
use App\User;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 11/3/15
 * Time: 11:15 AM
 */

trait AsLoggedInUser {

    /**
     * @before
     */
    public function logIn()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
    }

}