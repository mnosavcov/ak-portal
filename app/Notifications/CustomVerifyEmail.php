<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class CustomVerifyEmail extends VerifyEmailNotification
{
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject(__('mail-verify.Ověřte_svůj_e-mail_na_PVtrusted-cz'))
            ->line(__('mail-verify.Dobrý_den,'))
            ->line(__('mail-verify.děkujeme_Vám_za_zájem_o_služby_na_našem_portálu-_Abyste_mohli_svůj_účet_využívat,_ověřte_svůj_e-mail'))
            ->action(__('mail-verify.Ověřit_e-mail'), $verificationUrl)
            ->line(__('mail-verify.Pokud_nelze_na_odkaz_kliknout,_nebo_jste_zaznamenali_jiné_technické_problémy,_zkopírujte_následující_odkaz'))
            ->line($verificationUrl)
            ->line(__('mail-verify.Následně_ho_vložte_do_adresního_řádku_webového_prohlížeče_a_potvrďte_ho'))
            ->line(__('mail-verify.V_případě_jakýchkoliv_problémů,_požadavků_či_dotazů_se_nás_neváhejte_kontaktovat-_A_to_buď_odpovědí_přímo_na_tento_e-mail,_nebo_skrze_některou_z_možností_v_kontaktech_na_našem_portálu'))
            ->markdown('vendor.email');
    }
}
