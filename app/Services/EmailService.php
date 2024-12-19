<?php

namespace App\Services;

use App\Events\ProjectActualityAddedEvent;
use App\Events\ProjectAuctionBidNewEvent;
use App\Events\ProjectCommentAddedEvent;
use App\Events\ProjectCreatedEvent;
use App\Events\ProjectDocumentAddedEvent;
use App\Events\ProjectFinishedEvent;
use App\Events\ProjectFixedPriceBidsEndEvent;
use App\Events\ProjectOfferThePriceBidsEndEvent;
use App\Events\ProjectPreliminaryInterestBidsEndEvent;
use App\Mail\QueuedEmail;
use App\Models\EmailNotification;
use App\Models\NotificationEvent;
use App\Models\ProjectShow;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    private function InvestorList($event, $notification, $id, $checkTrack = true)
    {
        $userList = [];
        $users = User::where(
            function ($query) {
                $query->where('investor', true)
                    ->where('investor_status', 'verified');
            }
        );

        $users = $users->get();

        $notificationUsers = [];
        if($notification) {
            if(!is_array($notification)) {
                $notification = [$notification];
            }
            $notificationUsers = EmailNotification::whereIn('notify', $notification)->withoutGlobalScope('user_id')->get()->pluck('user_id')->toArray();
        }

        if($checkTrack) {
            $trackUsers = ProjectShow::where('track', 1)->where('project_id', $event->project->id)->get()->pluck('user_id')->toArray();
        }

        foreach ($users as $user) {
            if($notification && !in_array($user->id, $notificationUsers)) {
                continue;
            }

            if($checkTrack && !in_array($user->id, $trackUsers)) {
                continue;
            }

            if ($this->HasUserEvent($user, $event, $id)) {
                continue;
            }

            $userList[] = [
                'user' => $user,
                'email' => $user['email'],
                'toName' => $this->getToName($user),
            ];
        }

        return $userList;
    }

    private function getToName($user)
    {
        return trim(($user['name'] ?? '') . ' ' . ($user['surname'] ?? ''));
    }

    private function HasUserEvent($user, $event, $id): bool
    {
        $eventClass = get_class($event);
        return NotificationEvent::where('user_id', $user->id)->where('used_id', $id)->where('event_class', $eventClass)->count() > 0;
    }

    private function SetUserEvent(User $user, $event, $id)
    {
        $notificationEvent = new NotificationEvent();
        $notificationEvent->user_id = $user->id;
        $notificationEvent->used_id = $id;
        $notificationEvent->event_class = get_class($event);
        $notificationEvent->save();
    }

    private function translateSubject($project, $subject)
    {
        return Str::replace('{{ $project->title }}', $project->title, $subject);
    }

    // po spusteni projektu
    public function investorProjectCreated(ProjectCreatedEvent $event): void
    {
        $project = $event->project;
        if ($project->status !== 'publicated') {
            return;
        }

        $users = $this->InvestorList($event, 'investor_novy_projekt', $event->project->id, false);
        foreach ($users as $user) {
            $this->addEmailToQueue(
                $user['email'],
                $user['toName'],
                $this->translateSubject($project, __('template-mail-subject.investor-project-created')),
                'lang.' . app()->getLocale() . '.emails.investor-project-created',
                'lang.' . app()->getLocale() . '.emails.investor-project-created-text',
                [
                    'project' => $project,
                    'unsubscribeUrl' => route('unsubscribe', ['crypt' => (new ProfileService)->getUnsubscribeHash($user['user'], 'investor')]),
                ]
            );

            $this->SetUserEvent($user['user'], $event, $event->project->id);
        }
    }

    // po pridani dokumentu k projektu
    public function investorProjectDocumentAdded(ProjectDocumentAddedEvent $event): void
    {
        $project = $event->project;
        $document = $event->document;

        $users = $this->InvestorList($event, 'investor_nova_priloha_u_projektu', $event->document->id);
        foreach ($users as $user) {
            $this->addEmailToQueue(
                $user['email'],
                $user['toName'],
                $this->translateSubject($project, __('template-mail-subject.investor-project-document-new')),
                'lang.' . app()->getLocale() . '.emails.investor-project-document-new',
                'lang.' . app()->getLocale() . '.emails.investor-project-document-new-text',
                [
                    'project' => $project,
                    'document' => $document,
                    'unsubscribeUrl' => route('unsubscribe', ['crypt' => (new ProfileService)->getUnsubscribeHash($user['user'], 'investor')]),
                ]
            );

            $this->SetUserEvent($user['user'], $event, $event->document->id);
        }
    }

    // po pridani komentare k projektu
    public function investorProjectCommentAdded(ProjectCommentAddedEvent $event): void
    {
        $project = $event->project;

        $users = $this->InvestorList($event, 'investor_novy_komentar_u_projektu', $event->projectQuestion->id);
        foreach ($users as $user) {
            $this->addEmailToQueue(
                $user['email'],
                $user['toName'],
                $this->translateSubject($project, __('template-mail-subject.investor-project-comment-new')),
                'lang.' . app()->getLocale() . '.emails.investor-project-comment-new',
                'lang.' . app()->getLocale() . '.emails.investor-project-comment-new-text',
                [
                    'project' => $project,
                    'comment' => $event->projectQuestion,
                    'unsubscribeUrl' => route('unsubscribe', ['crypt' => (new ProfileService)->getUnsubscribeHash($user['user'], 'investor')]),
                ]
            );

            $this->SetUserEvent($user['user'], $event, $event->projectQuestion->id);
        }

        $this->addEmailToQueue(
            $event->project->user['email'],
            $this->getToName($event->project->user),
            $this->translateSubject($project, __('template-mail-subject.advertiser-project-comment-new')),
            'lang.' . app()->getLocale() . '.emails.advertiser-project-comment-new',
            'lang.' . app()->getLocale() . '.emails.advertiser-project-comment-new-text',
            [
                'project' => $project,
                'comment' => $event->projectQuestion,
            ]
        );

        $this->SetUserEvent($event->project->user, $event, $event->projectQuestion->id);
    }

    // po pridani aktuality k projektu
    public function investorProjectActualityAdded(ProjectActualityAddedEvent $event): void
    {
        $project = $event->project;

        $users = $this->InvestorList($event, 'investor_nova_aktualita_u_projektu', $event->projectActuality->id);
        foreach ($users as $user) {
            $this->addEmailToQueue(
                $user['email'],
                $user['toName'],
                $this->translateSubject($project, __('template-mail-subject.investor-project-actuality-new')),
                'lang.' . app()->getLocale() . '.emails.investor-project-actuality-new',
                'lang.' . app()->getLocale() . '.emails.investor-project-actuality-new-text',
                [
                    'project' => $project,
                    'unsubscribeUrl' => route('unsubscribe', ['crypt' => (new ProfileService)->getUnsubscribeHash($user['user'], 'investor')]),
                ]
            );

            $this->SetUserEvent($user['user'], $event, $event->projectActuality->id);
        }
    }

    // po pridani nabidky do aukce
    public function investorProjectAuctionBidNew(ProjectAuctionBidNewEvent $event): void
    {
        $project = $event->project;

        $users = $this->InvestorList($event, 'investor_novy_prihoz_v_aukci', $event->projectAuctionOffer->id);
        foreach ($users as $user) {
            $this->addEmailToQueue(
                $user['email'],
                $user['toName'],
                $this->translateSubject($project, __('template-mail-subject.investor-project-auction-bid-new')),
                'lang.' . app()->getLocale() . '.emails.investor-project-auction-bid-new',
                'lang.' . app()->getLocale() . '.emails.investor-project-auction-bid-new-text',
                [
                    'project' => $project,
                    'unsubscribeUrl' => route('unsubscribe', ['crypt' => (new ProfileService)->getUnsubscribeHash($user['user'], 'investor')]),
                ]
            );

            $this->SetUserEvent($user['user'], $event, $event->projectAuctionOffer->id);
        }

        $this->addEmailToQueue(
            $event->project->user['email'],
            $this->getToName($event->project->user),
            $this->translateSubject($project, __('template-mail-subject.advertiser-project-auction-bid-new')),
            'lang.' . app()->getLocale() . '.emails.advertiser-project-auction-bid-new',
            'lang.' . app()->getLocale() . '.emails.advertiser-project-auction-bid-new-text',
            ['project' => $project]
        );

        $this->SetUserEvent($event->project->user, $event, $event->projectAuctionOffer->id);
    }

    // konec sberu nabidek pro nabidne investor
    public function investorProjectOfferThePriceBidsEnd(ProjectOfferThePriceBidsEndEvent $event): void
    {
        $project = $event->project;

        $users = $this->InvestorList($event, 'investor_zmena_stavu_projektu', $event->project->id);
        foreach ($users as $user) {
            $this->addEmailToQueue(
                $user['email'],
                $user['toName'],
                $this->translateSubject($project, __('template-mail-subject.investor-project-offer-the-price-bids-end')),
                'lang.' . app()->getLocale() . '.emails.investor-project-offer-the-price-bids-end',
                'lang.' . app()->getLocale() . '.emails.investor-project-offer-the-price-bids-end-text',
                [
                    'project' => $project,
                    'unsubscribeUrl' => route('unsubscribe', ['crypt' => (new ProfileService)->getUnsubscribeHash($user['user'], 'investor')]),
                ]
            );

            $this->SetUserEvent($user['user'], $event, $event->project->id);
        }

        $this->addEmailToQueue(
            $event->project->user['email'],
            $this->getToName($event->project->user),
            $this->translateSubject($project, __('template-mail-subject.advertiser-project-offer-the-price-bids-end')),
            'lang.' . app()->getLocale() . '.emails.advertiser-project-offer-the-price-bids-end',
            'lang.' . app()->getLocale() . '.emails.advertiser-project-offer-the-price-bids-end-text',
            ['project' => $project]
        );

        $this->SetUserEvent($event->project->user, $event, $event->project->id);
    }

    // konec sberu pro fixni cenu, jistotu zaplatil jiny nez prvni
    public function investorProjectFixedPriceBidsEndEvent(ProjectFixedPriceBidsEndEvent $event): void
    {
        $project = $event->project;

        $users = $this->InvestorList($event, 'investor_zmena_stavu_projektu', $event->project->id);
        foreach ($users as $user) {
            $this->addEmailToQueue(
                $user['email'],
                $user['toName'],
                $this->translateSubject($project, __('template-mail-subject.investor-project-fixed-price-principal-pay-no-first')),
                'lang.' . app()->getLocale() . '.emails.investor-project-fixed-price-principal-pay-no-first',
                'lang.' . app()->getLocale() . '.emails.investor-project-fixed-price-principal-pay-no-first-text',
                [
                    'project' => $project,
                    'unsubscribeUrl' => route('unsubscribe', ['crypt' => (new ProfileService)->getUnsubscribeHash($user['user'], 'investor')]),
                ]
            );

            $this->SetUserEvent($user['user'], $event, $event->project->id);
        }

        $this->addEmailToQueue(
            $event->project->user['email'],
            $this->getToName($event->project->user),
            $this->translateSubject($project, __('template-mail-subject.advertiser-project-fixed-price-principal-pay-no-first')),
            'lang.' . app()->getLocale() . '.emails.advertiser-project-fixed-price-principal-pay-no-first',
            'lang.' . app()->getLocale() . '.emails.advertiser-project-fixed-price-principal-pay-no-first-text',
            ['project' => $project]
        );

        $this->SetUserEvent($event->project->user, $event, $event->project->id);
    }

    // konec sberu pro predbezny zajem
    public function investorProjectPreliminaryInterestBidsEnd(ProjectPreliminaryInterestBidsEndEvent $event): void
    {
        $project = $event->project;

        $users = $this->InvestorList($event, 'investor_zmena_stavu_projektu', $event->project->id);
        foreach ($users as $user) {
            $this->addEmailToQueue(
                $user['email'],
                $user['toName'],
                $this->translateSubject($project, __('template-mail-subject.investor-project-preliminary-interest-bids-end')),
                'lang.' . app()->getLocale() . '.emails.investor-project-preliminary-interest-bids-end',
                'lang.' . app()->getLocale() . '.emails.investor-project-preliminary-interest-bids-end-text',
                [
                    'project' => $project,
                    'unsubscribeUrl' => route('unsubscribe', ['crypt' => (new ProfileService)->getUnsubscribeHash($user['user'], 'investor')]),
                ]
            );

            $this->SetUserEvent($user['user'], $event, $event->project->id);
        }

        $this->addEmailToQueue(
            $event->project->user['email'],
            $this->getToName($event->project->user),
            $this->translateSubject($project, __('template-mail-subject.advertiser-project-preliminary-interest-bids-end')),
            'lang.' . app()->getLocale() . '.emails.advertiser-project-preliminary-interest-bids-end',
            'lang.' . app()->getLocale() . '.emails.advertiser-project-preliminary-interest-bids-end-text',
            ['project' => $project]
        );

        $this->SetUserEvent($event->project->user, $event, $event->project->id);
    }

    // ukonceni projektu
    public function investorProjectFinished(ProjectFinishedEvent $event): void
    {
        $project = $event->project;

        $users = $this->InvestorList($event, 'investor_zmena_stavu_projektu', $event->project->id);
        foreach ($users as $user) {
            $this->addEmailToQueue(
                $user['email'],
                $user['toName'],
                $this->translateSubject($project, __('template-mail-subject.investor-project-finished')),
                'lang.' . app()->getLocale() . '.emails.investor-project-finished',
                'lang.' . app()->getLocale() . '.emails.investor-project-finished-text',
                [
                    'project' => $project,
                    'unsubscribeUrl' => route('unsubscribe', ['crypt' => (new ProfileService)->getUnsubscribeHash($user['user'], 'investor')]),
                ]
            );

            $this->SetUserEvent($user['user'], $event, $event->project->id);
        }

        $this->addEmailToQueue(
            $event->project->user['email'],
            $this->getToName($event->project->user),
            $this->translateSubject($project, __('template-mail-subject.advertiser-project-finished')),
            'lang.' . app()->getLocale() . '.emails.advertiser-project-finished',
            'lang.' . app()->getLocale() . '.emails.advertiser-project-finished-text',
            ['project' => $project]
        );

        $this->SetUserEvent($event->project->user, $event, $event->project->id);
    }
}
