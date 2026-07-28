<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventNotification extends Notification
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Novo Evento: ' . $this->event->title)
            ->greeting('Olá!')
            ->line('Um novo evento foi agendado na escola.')
            ->line('**Evento:** ' . $this->event->title)
            ->line('**Data:** ' . $this->event->event_date->format('d/m/Y'))
            ->line('**Horário:** ' . $this->event->start_time->format('H:i') . ' - ' . $this->event->end_time->format('H:i'))
            ->action('Ver Detalhes', route('events.show', $this->event))
            ->line('Aguardamos sua participação!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'title' => $this->event->title,
            'event_date' => $this->event->event_date->format('d/m/Y'),
            'type' => $this->event->type,
            'message' => 'Novo evento agendado: ' . $this->event->title,
        ];
    }
}
