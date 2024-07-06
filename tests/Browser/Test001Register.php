<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Test001Register extends DuskTestCase
{
    /**
     * @dataProvider \Tests\DataSets\DataUsers::users()
     */
    public function testBasicExample($input): void
    {
        $this->browse(function (Browser $browser) use ($input) {
            $browser->visitRoute('login');

            $browser->assertSee('Přihlášení');

            $browser->clickLink('Registrujte se')
                ->assertUrlIs(route('register'));

            $browser->scrollIntoView('@register-chci-zalozit')
                ->click('@register-chci-zalozit');

            $browser->scrollIntoView('@register-investor')
                ->click('@register-investor');
            $browser->scrollIntoView('@register-advertiser')
                ->click('@register-advertiser');
            $browser->scrollIntoView('@register-realEstateBroker')
                ->click('@register-realEstateBroker');

            $browser->scrollIntoView('@register-potvrdit-vyber')
                ->click('@register-potvrdit-vyber');

            $browser->scrollIntoView('@register-confirm');

            $browser->type('email', $input['kontakt']['email'])
                ->type('phone_number', $input['kontakt']['phone_number'])
                ->type('password', 'password')
                ->type('password_confirmation', 'password');


            $browser->click('@register-confirm');

            $browser->press('Registrovat se');

            $browser->waitFor('@overeni-emailu-modal');

            $browser->driver->executeScript('
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "/logout", false);  // Synchronní požadavek (poslední parametr je false)
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector("meta[name=csrf-token]").getAttribute("content"));
                xhr.send();
            ');
        });
    }
}
