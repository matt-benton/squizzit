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
            $browser->visit('/#/signup')
                    ->type('email', 'matt@test.com')
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
            $browser->visit('/#/login')
                    ->type('email', 'matt@test.com')
                    ->type('password', 'Secret001')
                    ->click('@sign-in-button')
                    ->waitForText('Home')
                    ->assertSee('Home');
        });
    }
}
