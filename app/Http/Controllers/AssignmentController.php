<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Assignment;
use App\Models\User;
use App\Notifications\SecretSantaAssignmentNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AssignmentController extends Controller
{
    use AuthorizesRequests;

    public function makeAssignments(Event $event): RedirectResponse
    {
        $this->authorize('makeAssignments', $event);

        if ($event->assignments_made) {
            return back()->with('error', 'Dodele su već izvršene za ovaj event.');
        }

        $participants = $event->participants->pluck('id')->toArray();
        $participantCount = count($participants);

        if ($participantCount < 2) {
            return back()->with('error', 'Potrebno je minimum 2 učesnika za dodelu.');
        }

        try {
            DB::beginTransaction();

            // Kreiraj random dodelu - svako dobija jednu osobu
            $receivers = $participants;

            // Mešaj dok ne dobiješ validnu permutaciju (niko ne može sam sebi)
            $maxAttempts = 100;
            $attempt = 0;

            do {
                shuffle($receivers);
                $valid = true;

                // Proveri da niko nije dodeljen sam sebi
                for ($i = 0; $i < $participantCount; $i++) {
                    if ($participants[$i] === $receivers[$i]) {
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
            foreach ($participants as $index => $giverId) {
                Assignment::create([
                    'event_id' => $event->id,
                    'giver_id' => $giverId,
                    'receiver_id' => $receivers[$index],
                ]);
            }

            // Označi da su dodele izvršene
            $event->update([
                'assignments_made' => true,
                'assignment_date' => now(),
            ]);

            // Pošalji notifikacije svim učesnicima
            foreach ($participants as $index => $giverId) {
                $giver = User::find($giverId);
                $receiver = User::find($receivers[$index]);

                if ($giver && $receiver) {
                    $giver->notify(new SecretSantaAssignmentNotification($event, $receiver));
                }
            }

            DB::commit();

            return back()->with('success', 'Secret Santa dodele su uspešno izvršene i notifikacije su poslate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Greška pri dodeli: ' . $e->getMessage());
        }
    }
}
