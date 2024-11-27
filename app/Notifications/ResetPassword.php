<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to create the reset password URL.
     *
     * @var (\Closure(mixed, string): string)|null
     */
    public static $createUrlCallback;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var (\Closure(mixed, string): \Illuminate\Notifications\Messages\MailMessage)|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @param string $token
     * @return void
     */
    public function __construct($token = 'test-token')
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return $this->buildMailMessage($this->resetUrl($notifiable));
    }

    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param string $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(__('mail-ResetPassword.Obnova_zapomenutého_hesla'))
            ->line(__('mail-ResetPassword.Dobrý_den,'))
            ->line(__('mail-ResetPassword.tento_e-mail_jsme_vám_zaslali,_jelikož_jsme_obdrželi_žádost_o_obnovu_hesla_u_vašeho_účtu_na_PVtrusted-cz'))
            ->action(__('mail-ResetPassword.Obnovit_heslo'), $url)
            ->line(__('mail-ResetPassword.Odkaz_na_obnovu_hesla_je_platný_po_dobu_:count_minut', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(__('mail-ResetPassword.Pokud_máte_problém_kliknout_na_tlačítko_“Obnovit_heslo”,_zkopírujte_následující_URL_adresu_a_vložte_ji_do_adresního_řádku_ve_svém_internetovém_prohlížeči_a_potvrďte_ji:') . ' ' . $url)
            ->line(__('mail-ResetPassword.Pokud_jste_o_obnovu_hesla_nežádali,_nemusíte_na_tento_e-mail_reagovat-_V_případě,_že_byste_nevyžádané_výzvy_k_obnově_hesla_obdrželi_opakovaně,_kontaktujte_nás'))
            ->markdown('vendor.email');
    }

    /**
     * Get the reset URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }

    /**
     * Set a callback that should be used when creating the reset password button URL.
     *
     * @param \Closure(mixed, string): string $callback
     * @return void
     */
    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param \Closure(mixed, string): \Illuminate\Notifications\Messages\MailMessage $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
