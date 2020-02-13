<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use App\User;

class Login extends Page
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

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
        $browser->type('email', $this->user->email)
                ->type('password', 'password')
                ->click('@sign-in-button')
                ->waitForText('Invites');
    }
}
