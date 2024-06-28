<?php

namespace App\Services;

class HomepageButtonsService
{
    public function getChciInvestovatUrl()
    {
        if (auth()->guest()) {
            return route('projects.index');
        }

        if ($this->isInvestorOnly()) {
            return route('projects.index');
        }

        if ($this->isAdvertiserOnly() || $this->isRealEstateBrokerOnly() || $this->isAdvertiserAndRealEstateBrokerOnly()) {
            // defaultni akce
        }

        if ($this->isAll()) {
            return route('projects.index');
        }

        return 'javascript:void(0);" @click.prevent="$dispatch(\'open-modal\', {name: \'hp-message\', message: `Nevystupujete v roli investora. V <a href=\'' .
            route('profile.edit', ['add' => 'investor']) . '\' class=\'text-app-blue underline hover:no-underline\'>nastavení účtu</a> přidejte nový typ účtu`})';
    }

    public function getChciNabidnoutUrl()
    {
        if (auth()->guest()) {
            return route('login');
        }

        if ($this->isInvestorOnly()) {
            // defaultni akce
        }

        if ($this->isAdvertiserOnly()) {
            return route('projects.create', ['accountType' => 'advertiser']);
        }

        if ($this->isRealEstateBrokerOnly()) {
            return route('projects.create', ['accountType' => 'real-estate-broker']);
        }

        if ($this->isAdvertiserAndRealEstateBrokerOnly() || $this->isAll()) {
            return route('projects.create.select');
        }

        return 'javascript:void(0);" @click.prevent="$dispatch(\'open-modal\', {name: \'hp-message\', message: `Nevystupujete v roli nabízejícího a ani realitního makléře. V <a href=\'' .
            route('profile.edit', ['add' => 'no-investor']) . '\' class=\'text-app-blue underline hover:no-underline\'>nastavení účtu</a> přidejte nový typ účtu`})';
    }

    private function isInvestorOnly()
    {
        if (auth()->user()->advertiser) {
            return false;
        }
        if (auth()->user()->real_estate_broker) {
            return false;
        }

        return (bool)auth()->user()->investor;
    }

    private function isAdvertiserOnly()
    {
        if (auth()->user()->investor) {
            return false;
        }
        if (auth()->user()->real_estate_broker) {
            return false;
        }

        return (bool)auth()->user()->advertiser;
    }

    private function isRealEstateBrokerOnly()
    {
        if (auth()->user()->advertiser) {
            return false;
        }
        if (auth()->user()->investor) {
            return false;
        }

        return (bool)auth()->user()->real_estate_broker;
    }

    private function isAdvertiserAndRealEstateBrokerOnly()
    {
        if (auth()->user()->investor) {
            return false;
        }

        return auth()->user()->advertiser && auth()->user()->real_estate_broker;
    }

    private function isAll()
    {
        return auth()->user()->investor && auth()->user()->advertiser && auth()->user()->real_estate_broker;
    }
}
