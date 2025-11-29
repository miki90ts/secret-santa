<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        $validated = $request->validated();

        Comment::create([
            'receiver_id' => $validated['receiver_id'],
            'event_id' => $validated['event_id'],
            'user_id' => auth()->id(),
            'title' => $validated['title'],
        ]);

        return redirect(route('dashboard'))->with('message', [
            'body' => 'Hvala na pomoći',
            'type' => 'success'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
