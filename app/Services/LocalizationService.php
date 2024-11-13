<?php

namespace App\Services;

class LocalizationService {
    public $translates = [];

    function __construct() {
        $this->translates[] = __('kategorie.Auction-Title');
        $this->translates[] = __('kategorie.Auction-Description');

        $this->translates[] = __('kategorie.FixedPrice-Title');
        $this->translates[] = __('kategorie.FixedPrice-Description');

        $this->translates[] = __('kategorie.OfferThePrice-Title');
        $this->translates[] = __('kategorie.OfferThePrice-Description');

        $this->translates[] = __('kategorie.PreliminaryInterest-Title');
        $this->translates[] = __('kategorie.PreliminaryInterest-Description');

        $this->translates[] = __('auth.password');
    }
}
