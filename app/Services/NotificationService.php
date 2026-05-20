<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GeneralNotification;

class NotificationService {
    public function send(User $user, string $type, string $title, string $message, array $data = [], ?string $url = null): void {
        $user->notify(new GeneralNotification($type, $title, $message, $data, $url));
    }

    public function sendToMany(array $users, string $type, string $title, string $message, array $data = []): void {
        foreach ($users as $user) {
            $this->send($user, $type, $title, $message, $data);
        }
    }
}
