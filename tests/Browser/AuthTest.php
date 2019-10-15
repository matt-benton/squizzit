<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use App\User;
use Hash;

class SignUpTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testSignUp()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/#/auth/signup')
                    ->type('email', 'matt@test.com')
                    ->click('#password-input')      // click into the next input
                    ->pause(1000)                   // wait for the axios request to return
                    ->type('password', 'Secret001')
                    ->type('password_confirmation', 'Secret001')
                    ->click('@sign-up-button')
                    ->waitForText('Home')
                    ->assertSee('Home');
        });
    }

    public function testSignIn()
    {
        User::create([
            'email' => 'matt@test.com',
            'password' => Hash::make('Secret001'),
            'api_token' => Str::random(60),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/#/auth/login')
                    ->type('email', 'matt@test.com')
                    ->type('password', 'Secret001')
                    ->click('@sign-in-button')
                    ->waitForText('Home')
                    ->assertSee('Home');
        });
    }

    public function testDuplicateEmailIsBlocked()
    {
        $user = factory(\App\User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/#/auth/signup')
                    ->type('email', $user->email)
                    ->click('#password-input')
                    ->waitForText('An account with this email address is already registered.')
                    ->assertSee('An account with this email address is already registered.');
        });
    }
}
