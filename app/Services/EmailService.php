<?php

namespace App\Services;

use App\Mail\QueuedEmail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function userChangeTypeStatuses($user)
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

    private function addEmailToQueue($to, $toName, $subject, $view, $text, $data = [])
    {
        // Data, která chcete předat e-mailové šabloně
        $data['subject'] = $subject;
        $data['view'] = $view;
        $data['text'] = $text;

        // Přidání e-mailu do fronty
        Mail::to($to, trim($toName))->queue(new QueuedEmail($data));

        return response()->json(['status' => 'E-mail přidán do fronty']);
    }

    private function userChangeInvestorStatus($user)
    {
        if (!$user->investor) {
            return;
        }

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        if ($user['investor_status_email_notification'] === 'verified') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                'Váš účet investora byl ověřen',
                'lang.' . app()->getLocale() . '.emails.user-verify-investor-verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-investor-verified-text',
            );
        }

        if ($user['investor_status_email_notification'] === 'denied') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                'Váš účet investora nebyl ověřen',
                'lang.' . app()->getLocale() . '.emails.user-verify-investor-not_verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-investor-not_verified-text',
            );
        }
    }

    private function userChangeAdvertiserStatus($user)
    {
        if (!$user->advertiser) {
            return;
        }

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        if ($user['advertiser_status_email_notification'] === 'verified') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                'Váš účet nabízejícího byl ověřen',
                'lang.' . app()->getLocale() . '.emails.user-verify-advertiser-verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-advertiser-verified-text',
            );
        }

        if ($user['advertiser_status_email_notification'] === 'denied') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                'Váš účet nabízejícího nebyl ověřen',
                'lang.' . app()->getLocale() . '.emails.user-verify-advertiser-not_verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-advertiser-not_verified-text',
            );
        }
    }

    private function userChangeRealEstateBrokerStatus($user)
    {
        if (!$user->real_estate_broker) {
            return;
        }

        $toName = ($user['name'] ?? '') . ' ' . ($user['surname'] ?? '');

        if ($user['real_estate_broker_status_email_notification'] === 'verified') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                'Váš účet realitního makléře byl ověřen',
                'lang.' . app()->getLocale() . '.emails.user-verify-real_estate_broker-verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-real_estate_broker-verified-text',
            );
        }

        if ($user['real_estate_broker_status_email_notification'] === 'denied') {
            $this->addEmailToQueue(
                $user['email'],
                $toName,
                'Váš účet realitního makléře nebyl ověřen',
                'lang.' . app()->getLocale() . '.emails.user-verify-real_estate_broker-not_verified',
                'lang.' . app()->getLocale() . '.emails.user-verify-real_estate_broker-not_verified-text',
            );
        }
    }
}
