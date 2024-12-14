<?php

namespace App\Services;

use App\Mail\QueuedEmail;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function userChangeTypeStatuses($user): void
    {
        if ($user->check_status !== 'verified') {
            return;
        }

        $this->userChangeInvestorStatus($user);
        $this->userChangeAdvertiserStatus($user);
        $this->userChangeRealEstateBrokerStatus($user);

        if ($user->investor) {
            $user->investor_status_email_notification = null;
        }
        if ($user->advertiser) {
            $user->advertiser_status_email_notification = null;
        }
        if ($user->real_estate_broker) {
            $user->real_estate_broker_status_email_notification = null;
        }

        $user->save();
    }

    private function addEmailToQueue($to, $toName, $subject, $view, $text, $params = []): void
    {
        $data = [];
        // Data, která chcete předat e-mailové šabloně
        $data['subject'] = $subject;
        $data['view'] = $view;
        $data['text'] = $text;

        // Přidání e-mailu do fronty
        Mail::to($to, trim($toName))->queue(new QueuedEmail($data, $params));

        response()->json(['status' => 'E-mail přidán do fronty']);
    }

    private function userChangeInvestorStatus($user): void
    {
        if (!$user->investor) {
            return;
        }

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        if ($user['investor_status_email_notification'] === 'verified') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                __('template-mail-subject.user-verify-investor-verified'),
                'lang.' . app()->getLocale() . '.emails.user-verify-investor-verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-investor-verified-text',
            );
        }

        if ($user['investor_status_email_notification'] === 'denied') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                __('template-mail-subject.user-verify-investor-not_verified'),
                'lang.' . app()->getLocale() . '.emails.user-verify-investor-not_verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-investor-not_verified-text',
            );
        }
    }

    private function userChangeAdvertiserStatus($user): void
    {
        if (!$user->advertiser) {
            return;
        }

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        if ($user['advertiser_status_email_notification'] === 'verified') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                __('template-mail-subject.user-verify-advertiser-verified'),
                'lang.' . app()->getLocale() . '.emails.user-verify-advertiser-verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-advertiser-verified-text',
            );
        }

        if ($user['advertiser_status_email_notification'] === 'denied') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                __('template-mail-subject.user-verify-advertiser-not_verified'),
                'lang.' . app()->getLocale() . '.emails.user-verify-advertiser-not_verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-advertiser-not_verified-text',
            );
        }
    }

    private function userChangeRealEstateBrokerStatus($user): void
    {
        if (!$user->real_estate_broker) {
            return;
        }

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        if ($user['real_estate_broker_status_email_notification'] === 'verified') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                __('template-mail-subject.user-verify-real_estate_broker-verified'),
                'lang.' . app()->getLocale() . '.emails.user-verify-real_estate_broker-verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-real_estate_broker-verified-text',
            );
        }

        if ($user['real_estate_broker_status_email_notification'] === 'denied') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                __('template-mail-subject.user-verify-real_estate_broker-not_verified'),
                'lang.' . app()->getLocale() . '.emails.user-verify-real_estate_broker-not_verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-real_estate_broker-not_verified-text',
            );
        }
    }

    // po spusteni projektu
    public function investorProjectCreated(Project $project): void
    {
        $user = User::where('investor', 1)->first();

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        $this->addEmailToQueue(
            $user['email'],
            $toName,
            __('template-mail-subject.investor-project-created'),
            'lang.' . app()->getLocale() . '.emails.investor-project-created',
            'lang.' . app()->getLocale() . '.emails.investor-project-created-text',
            ['project' => $project]
        );
    }

    // po pridani dokumentu k projektu
    public function investorProjectDocumentAdded(Project $project, ProjectFile $document): void
    {
        $user = User::where('investor', 1)->first();

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        $this->addEmailToQueue(
            $user['email'],
            $toName,
            __('template-mail-subject.investor-project-document-new'),
            'lang.' . app()->getLocale() . '.emails.investor-project-document-new',
            'lang.' . app()->getLocale() . '.emails.investor-project-document-new-text',
            [
                'project' => $project,
                'document' => $document,
            ]
        );
    }

    // po pridani komentare k projektu
    public function investorProjectCommentAdded(Project $project): void
    {
        $user = User::where('investor', 1)->first();

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        $this->addEmailToQueue(
            $user['email'],
            $toName,
            __('template-mail-subject.investor-project-comment-new'),
            'lang.' . app()->getLocale() . '.emails.investor-project-comment-new',
            'lang.' . app()->getLocale() . '.emails.investor-project-comment-new-text',
            ['project' => $project]
        );
    }

    // po pridani aktuality k projektu
    public function investorProjectActualityAdded(Project $project): void
    {
        $user = User::where('investor', 1)->first();

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        $this->addEmailToQueue(
            $user['email'],
            $toName,
            __('template-mail-subject.investor-project-actuality-new'),
            'lang.' . app()->getLocale() . '.emails.investor-project-actuality-new',
            'lang.' . app()->getLocale() . '.emails.investor-project-actuality-new-text',
            ['project' => $project]
        );
    }

    // po pridani nabidky do aukce
    public function investorProjectAuctionBidNew(Project $project): void
    {
        $user = User::where('investor', 1)->first();

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        $this->addEmailToQueue(
            $user['email'],
            $toName,
            __('template-mail-subject.investor-project-auction-bid-new'),
            'lang.' . app()->getLocale() . '.emails.investor-project-auction-bid-new',
            'lang.' . app()->getLocale() . '.emails.investor-project-auction-bid-new-text',
            ['project' => $project]
        );
    }

    // konec sberu nabidek pro nabidne investor
    public function investorProjectOfferThePriceBidsEnd(Project $project): void
    {
        $user = User::where('investor', 1)->first();

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        $this->addEmailToQueue(
            $user['email'],
            $toName,
            __('template-mail-subject.investor-project-offer-the-price-bids-end'),
            'lang.' . app()->getLocale() . '.emails.investor-project-offer-the-price-bids-end',
            'lang.' . app()->getLocale() . '.emails.investor-project-offer-the-price-bids-end-text',
            ['project' => $project]
        );
    }

    // konec sberu pro predbezny zajem
    public function investorProjectPreliminaryInterestBidsEnd(Project $project): void
    {
        $user = User::where('investor', 1)->first();

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        $this->addEmailToQueue(
            $user['email'],
            $toName,
            __('template-mail-subject.investor-project-preliminary-interest-bids-end'),
            'lang.' . app()->getLocale() . '.emails.investor-project-preliminary-interest-bids-end',
            'lang.' . app()->getLocale() . '.emails.investor-project-preliminary-interest-bids-end-text',
            ['project' => $project]
        );
    }

    // konec sberu pro fixni cenu, jistotu zaplatil jiny nez prvni
    public function investorProjectFixedPricePrincipalPayNoFirst(Project $project): void
    {
        $user = User::where('investor', 1)->first();

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        $this->addEmailToQueue(
            $user['email'],
            $toName,
            __('template-mail-subject.investor-project-fixed-price-principal-pay-no-first'),
            'lang.' . app()->getLocale() . '.emails.investor-project-fixed-price-principal-pay-no-first',
            'lang.' . app()->getLocale() . '.emails.investor-project-fixed-price-principal-pay-no-first-text',
            ['project' => $project]
        );
    }

    // ukonceni projektu
    public function investorProjectFinished(Project $project): void
    {
        $user = User::where('investor', 1)->first();

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        $this->addEmailToQueue(
            $user['email'],
            $toName,
            __('template-mail-subject.investor-project-finished'),
            'lang.' . app()->getLocale() . '.emails.investor-project-finished',
            'lang.' . app()->getLocale() . '.emails.investor-project-finished-text',
            ['project' => $project]
        );
    }
}
