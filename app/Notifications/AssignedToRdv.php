<?php

namespace App\Notifications;

use App\Models\Rdv;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AssignedToRdv extends Notification
{
    use Queueable;

    protected $rdv;

    /**
     * Create a new notification instance.
     */
    public function __construct(Rdv $rdv)
    {
        $this->rdv = $rdv;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // Send via email and store in the database
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouveau Rendez-vous Assigné')
            ->line('Un nouveau rendez-vous vous a été assigné.')
            ->line('Contact: ' . $this->rdv->contact->nom . ' ' . $this->rdv->contact->prenom)
            ->line('Date: ' . $this->rdv->date->format('d/m/Y H:i'))
            ->action('Voir le Rendez-vous', route('rdvs.show', $this->rdv->id))
            ->line('Merci de gérer ce rendez-vous.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'rdv_id' => $this->rdv->id,
            'contact_name' => $this->rdv->contact->nom . ' ' . $this->rdv->contact->prenom,
            'date' => $this->rdv->date->format('d/m/Y H:i'),
        ];
    }
}
