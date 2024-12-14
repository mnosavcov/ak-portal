<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QueuedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $params;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $params = [])
    {
        $this->data = $data;
        $this->params = $params;
    }

    public function build()
    {
        return $this->view($this->data['view'], $this->params)
            ->text($this->data['text'])
            ->subject($this->data['subject'])
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    }

//    /**
//     * Get the message envelope.
//     */
//    public function envelope(): Envelope
//    {
//        return new Envelope(
//            subject: 'Send Queued Email',
//        );
//    }

//    /**
//     * Get the message content definition.
//     */
//    public function content(): Content
//    {
//        return new Content(
//            view: 'view.name',
//        );
//    }

//    /**
//     * Get the attachments for the message.
//     *
//     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
//     */
//    public function attachments(): array
//    {
//        return [];
//    }
}
