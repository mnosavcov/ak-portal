<?php

namespace App\Services;

class HomepageButtonsService
{
    public function getChciInvestovatUrl()
    {
        $usersService = new UsersService();

        if (auth()->guest()) {
            return route('projects.index');
        }

        if ($usersService->isInvestorOnly()) {
            return route('projects.index');
        }

        if ($usersService->isAdvertiserOnly() || $usersService->isRealEstateBrokerOnly() || $usersService->isAdvertiserAndRealEstateBrokerOnly()) {
            // defaultni akce
        }

        if ($usersService->isAll()) {
            return route('projects.index');
        }

        if (auth()->user()->investor) {
            return route('projects.index');
        }

        return 'javascript:void(0);" @click.prevent="$dispatch(\'open-modal\', {name: \'hp-message\', message: `' . __('Nevystupujete v roli investora.') . ' ' . __('V') . ' <a href=\'' .
            route('profile.edit', ['add' => 'investor']) . '\' class=\'text-app-blue underline hover:no-underline\'>' . __('nastavení účtu') . '</a> ' . __('přidejte nový typ účtu') . '`})';
    }

    public function getChciNabidnoutUrl()
    {
        $usersService = new UsersService();

        if (auth()->guest()) {
            return route('login');
        }

        if ($usersService->isInvestorOnly()) {
            // defaultni akce
        }

        if ($usersService->isAdvertiserOnly()) {
            return route('projects.create', ['accountType' => 'advertiser']);
        }

        if ($usersService->isRealEstateBrokerOnly()) {
            return route('projects.create', ['accountType' => 'real-estate-broker']);
        }

        if ($usersService->isAdvertiserAndRealEstateBrokerOnly() || $usersService->isAll()) {
            return route('projects.create.select');
        }

        if (auth()->user()->advertiser && auth()->user()->real_estate_broker) {
            return route('projects.index');
        }

        if (auth()->user()->advertiser) {
            return route('projects.create', ['accountType' => 'advertiser']);
        }

        if (auth()->user()->real_estate_broker) {
            return route('projects.create', ['accountType' => 'real-estate-broker']);
        }


        return 'javascript:void(0);" @click.prevent="$dispatch(\'open-modal\', {name: \'hp-message\', message: `' . __('Nevystupujete v roli nabízejícího a ani realitního makléře.') . ' ' . __('V') . ' <a href=\'' .
    route('profile.edit', ['add' => 'no-investor']) . '\' class=\'text-app-blue underline hover:no-underline\'>' . __('nastavení účtu') . '</a> ' . __('přidejte nový typ účtu') . '`})';
    }
}
