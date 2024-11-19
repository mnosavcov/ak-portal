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
            ->subject(__('mail-registration_complete.Dokončená_registrace_na_PVtrusted-cz'))
            ->line(__('mail-registration_complete.Dobrý_den,'))
            ->line(__('mail-registration_complete.děkujeme_za_ověření_e-mailu-_Potvrzujeme,_že_Vaše_registrace_byla_úspěšně_dokončena'))
            ->line(__('mail-registration_complete.Přihlašovat_se_můžete_na_následující_stránce:') . ' <a href="'. route('login') . '">'. route('login') . '</a> ' . __('mail-registration_complete.pomocí_svého_e-mailu_a_zvoleného_hesla'))
            ->line(__('mail-registration_complete.Abyste_mohli_využívat_náš_portál_naplno_a_mít_přístup_ke_všem_funkcím_a_informacím,_musíte') . ' <strong>' . __('mail-registration_complete.ověřit_svůj_účet') . '</strong>. ' . __('mail-registration_complete.Tento_proces_můžete_realizovat_v_“Nastavení_účtu”_po_přihlášení'))
            ->line(__('mail-registration_complete.V_případě_jakýchkoliv_problémů,_požadavků_či_dotazů_se_nás_neváhejte_kontaktovat-_A_to_buď_odpovědí_přímo_na_tento_e-mail,_nebo_skrze_některou_z_možností_v_kontaktech_na_našem_portálu'))
            ->markdown('vendor.email');
    }
}
