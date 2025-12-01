<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Event;
use App\Models\Assignment;
use App\Notifications\SecretSantaAssignmentNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestSecretSantaFlow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:secret-santa-flow {event_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test kompletnog Secret Santa procesa sa notifikacijama';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventId = $this->argument('event_id');
        $event = Event::find($eventId);

        if (!$event) {
            $this->error('Event sa ID ' . $eventId . ' ne postoji!');
            return 1;
        }

        $this->info("Testiram Secret Santa proces za event: {$event->name}");

        // Pribavi učesnike
        $participants = $event->participants;

        if ($participants->count() < 2) {
            $this->error('Event nema dovoljno učesnika (minimum 2)');
            return 1;
        }

        $this->info("Pronađeno {$participants->count()} učesnika:");

        foreach ($participants as $participant) {
            $this->line("- {$participant->name} ({$participant->email})");
        }

        if ($event->assignments_made) {
            $this->warn('Dodele su već napravljene za ovaj event. Brisanje postojećih...');
            Assignment::where('event_id', $event->id)->delete();
            $event->update(['assignments_made' => false, 'assignment_date' => null]);
        }

        $this->info("\nKreiram dodele...");

        // Simuliraj makeAssignments proces
        $participantIds = $participants->pluck('id')->toArray();
        $participantCount = count($participantIds);

        try {
            DB::beginTransaction();

            // Kreiraj random dodelu - svako dobija jednu osobu
            $receivers = $participantIds;

            // Mešaj dok ne dobiješ validnu permutaciju (niko ne može sam sebi)
            $maxAttempts = 100;
            $attempt = 0;

            do {
                shuffle($receivers);
                $valid = true;

                // Proveri da niko nije dodeljen sam sebi
                for ($i = 0; $i < $participantCount; $i++) {
                    if ($participantIds[$i] === $receivers[$i]) {
                        $valid = false;
                        break;
                    }
                }

                $attempt++;
                if ($attempt >= $maxAttempts) {
                    throw new \Exception('Nije moguće napraviti validnu dodelu.');
                }
            } while (!$valid);

            // Kreiraj assignments
            $assignments = [];
            foreach ($participantIds as $index => $giverId) {
                $assignment = Assignment::create([
                    'event_id' => $event->id,
                    'giver_id' => $giverId,
                    'receiver_id' => $receivers[$index],
                ]);
                $assignments[] = $assignment;
            }

            // Označi da su dodele izvršene
            $event->update([
                'assignments_made' => true,
                'assignment_date' => now(),
            ]);

            $this->info("Dodele su kreirane:");
            foreach ($assignments as $assignment) {
                $giver = User::find($assignment->giver_id);
                $receiver = User::find($assignment->receiver_id);
                $this->line("→ {$giver->name} daruje {$receiver->name}");
            }

            // Pošalji notifikacije svim učesnicima
            $this->info("\nSlanje notifikacija...");
            foreach ($participantIds as $index => $giverId) {
                $giver = User::find($giverId);
                $receiver = User::find($receivers[$index]);

                if ($giver && $receiver) {
                    $this->line("Šalje se notifikacija za {$giver->name} → {$receiver->name}");
                    $giver->notify(new SecretSantaAssignmentNotification($event, $receiver));
                }
            }

            DB::commit();

            $this->info("\n✅ Secret Santa dodele su uspešno izvršene i notifikacije su poslate!");
            $this->info("Broj učesnika: {$participantCount}");
            $this->info("Broj dodela: " . count($assignments));
            $this->info("Broj notifikacija: {$participantCount}");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Greška pri dodeli: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
