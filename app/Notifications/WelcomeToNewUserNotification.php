<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeToNewUserNotification extends Notification
{
    use Queueable;

    private $top_users;
    public function __construct($top_users)
    {
        $this->top_users = $top_users;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $list = $this->top_users->map(fn ($a) => "<a href=\"" . route('user.show', $a->id) . "\">$a->name</a>")->implode(', ');

        return (new MailMessage)
            ->greeting("Hello {$notifiable->name},")
            ->subject('Welcome to Harbour Space Social Network')
            ->line('This is a place to share your ideas and best pictures')
            ->line('Here is a list of our top ' . count($this->top_users) . ' users with more posts:<br/>' . $list . '.')
            ->action('Share with your friends on campus', url('/'))
            ->line('Thank you for using our application!')
            ->salutation('Greetings from the Yordan');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
