<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Event;
use App\Notifications\SecretSantaAssignmentNotification;
use Illuminate\Console\Command;

class TestSecretSantaNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:secret-santa-notification {user_id} {event_id} {receiver_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Secret Santa notification email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $eventId = $this->argument('event_id');
        $receiverId = $this->argument('receiver_id');

        $user = User::find($userId);
        $event = Event::find($eventId);
        $receiver = User::find($receiverId);

        if (!$user || !$event || !$receiver) {
            $this->error('User, event, ili receiver nije pronađen!');
            return 1;
        }

        $this->info("Šalje se test notifikacija...");
        $this->info("Od: {$user->name} ({$user->email})");
        $this->info("Event: {$event->name}");
        $this->info("Prima poklon od: {$receiver->name}");

        $user->notify(new SecretSantaAssignmentNotification($event, $receiver));

        $this->info("Notifikacija je poslata!");
        return 0;
    }
}
