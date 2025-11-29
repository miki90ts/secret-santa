<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Assignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
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

            DB::commit();

            return back()->with('success', 'Secret Santa dodele su uspešno izvršene!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Greška pri dodeli: ' . $e->getMessage());
        }
    }

    /**
     * Show the user's assignment (who they need to buy a gift for).
     */
    public function myAssignment(Event $event)
    {
        $assignment = Assignment::where('event_id', $event->id)
            ->where('giver_id', auth()->id())
            ->with(['receiver.wishes' => function ($query) use ($event) {
                $query->where('event_id', $event->id)
                    ->with('comments.user');
            }])
            ->first();

        if (!$assignment) {
            return back()->with('error', 'Nemate dodelu za ovaj event.');
        }

        return inertia('Assignments/Show', [
            'assignment' => $assignment,
            'event' => $event,
        ]);
    }
}
