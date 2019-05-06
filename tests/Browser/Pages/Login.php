<?php

namespace Tests\Browser\Pages;

use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use App\User;
use Hash;

class Login extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/#/auth/login';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        // NOTE: This is not working with the hashed urls
        // $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }

    public function loginUser(Browser $browser)
    {
        $user = User::create([
            'email' => 'matt@test.com',
            'password' => Hash::make('Secret001'),
            'api_token' => Str::random(60),
        ]);

        $browser->type('email', 'matt@test.com')
                ->type('password', 'Secret001')
                ->click('@sign-in-button')
                ->waitForText('Home');
    }
}
