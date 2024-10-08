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
    public function __construct($token)
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
            ->subject(Lang::get('Obnova zapomenutého hesla'))
            ->line(Lang::get('Dobrý den,'))
            ->line(Lang::get('tento e-mail jsme vám zaslali, jelikož jsme obdrželi žádost o obnovu hesla u vašeho účtu na PVtrusted.cz.'))
            ->action(Lang::get('Obnovit heslo'), $url)
            ->line(Lang::get('Odkaz na obnovu hesla je platný po dobu :count minut.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('Pokud máte problém kliknout na tlačítko “Obnovit heslo”, zkopírujte následující URL adresu a vložte ji do adresního řádku ve svém internetovém prohlížeči a potvrďte ji: ' . $url))
            ->line(Lang::get('Pokud jste o obnovu hesla nežádali, nemusíte na tento e-mail reagovat. V případě, že byste nevyžádané výzvy k obnově hesla obdrželi opakovaně, kontaktujte nás.'))
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
