<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignedToRdv extends Notification implements ShouldQueue
{
    use Queueable;

    private $rdv;

    /**
     * Create a new notification instance.
     */
    public function __construct($rdv)
    {
        $this->rdv = $rdv;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau Rendez-vous Assigné')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un nouveau rendez-vous vous a été assigné.')
            ->line('**Contact**: ' . $this->rdv->contact->nom . ' ' . $this->rdv->contact->prenom)
            ->line('**Date**: ' . $this->rdv->date->format('d/m/Y H:i'))
            ->line('**Type**: ' . $this->rdv->type)
            ->action('Voir le Rendez-vous', url('/rdvs/' . $this->rdv->id))
            ->line('Merci de vérifier les détails du rendez-vous.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'rdv_id' => $this->rdv->id,
            'contact_name' => $this->rdv->contact->nom . ' ' . $this->rdv->contact->prenom,
            'date' => $this->rdv->date,
            'type' => $this->rdv->type,
        ];
    }
}
