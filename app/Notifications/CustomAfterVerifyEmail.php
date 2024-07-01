<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class CustomAfterVerifyEmail extends VerifyEmailNotification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Dokončená registrace na PVtrusted.cz')
            ->line('Dobrý den,')
            ->line('děkujeme za ověření e-mailu. Potvrzujeme, že Vaše registrace byla úspěšně dokončena.')
            ->line('Přihlašovat se můžete na následující stránce: <a href="'. route('login') . '">'. route('login') . '</a> pomocí svého e-mailu a zvoleného hesla.')
            ->line('Abyste mohli využívat náš portál naplno a mít přístup ke všem funkcím a informacím, musíte <strong>ověřit svůj účet</strong>. Tento proces můžete realizovat v “Nastavení účtu” po přihlášení')
            ->line('V případě jakýchkoliv problémů, požadavků či dotazů se nás neváhejte kontaktovat. A to buď odpovědí přímo na tento e-mail, nebo skrze některou z možností v kontaktech na našem portálu.')
            ->markdown('vendor.email');
    }
}
