<?php

namespace App\Mail;

use App\Models\Configuracion;
use App\Models\Iglesia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DefaultMail extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData, $version, $iglesia;
    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
      $this->mailData=$mailData;
      $this->mailData->subject = isset($mailData->subject) ? $mailData->subject : 'Notificación';

      $configuracion = Configuracion::find(1);
      $this->version = $configuracion->version;

      $this->iglesia = Iglesia::find(1);

      if(!isset($mailData->banner))
      {
        $this->mailData->banner = $configuracion->version == 1
        ? Storage::url($configuracion->ruta_almacenamiento.'/img/email/base.png')
        : Storage::url($configuracion->ruta_almacenamiento.'/img/email/base.png');
      }

    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailData->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.default-mail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
