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
            ->subject('Ověřte svůj e-mail na PVtrusted.cz')
            ->line('Dobrý den,')
            ->line('děkujeme Vám za zájem o služby na našem portálu. Abyste mohli svůj účet využívat, ověřte svůj e-mail.')
            ->action('Ověřit e-mail', $verificationUrl)
            ->line('Pokud nelze na odkaz kliknout, nebo jste zaznamenali jiné technické problémy, zkopírujte následující odkaz.')
            ->line($verificationUrl)
            ->line('Následně ho vložte do adresního řádku webového prohlížeče a potvrďte ho.')
            ->line('V případě jakýchkoliv problémů, požadavků či dotazů se nás neváhejte kontaktovat. A to buď odpovědí přímo na tento e-mail, nebo skrze některou z možností v kontaktech na našem portálu.')
            ->markdown('vendor.email');
    }
}
