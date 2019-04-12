<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SignUpTest extends DuskTestCase
{
    public function testSignUp()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/#/signup')
                    ->type('email', 'matt@test.com')
                    ->type('password', 'Secret001')
                    ->type('password_confirmation', 'Secret001')
                    ->click('@sign-up-button')
                    ->waitForText('Home')
                    ->assertSee('Home');
        });
    }
}
