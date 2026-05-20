<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification implements ShouldQueue {
    use Queueable;
    public function __construct(
        public string $type,
        public string $title,
        public string $message,
        public array $data = [],
        public ?string $url = null
    ) {}
    public function via($notifiable): array { return ['database']; }
    public function toArray($notifiable): array {
        return ['type'=>$this->type,'title'=>$this->title,'message'=>$this->message,'url'=>$this->url,'data'=>$this->data];
    }
    public function toMail($notifiable): MailMessage {
        return (new MailMessage)->subject($this->title)->line($this->message)->action('View', $this->url ?? url('/'));
    }
}
