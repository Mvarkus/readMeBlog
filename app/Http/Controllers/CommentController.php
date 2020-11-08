<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\ {
    Post,
    Comment
};

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validData = $request->validate([
            'content' => 'required|string'
        ]);

        $comment = new Comment(['content' => $validData['content']]);
        $comment->post()->associate($post);
        $comment->user()->associate(Auth::user());

        $comment->save();

        session()->flash('commentResult', [
            'message' => 'Comment added successfully, it will appear after approval',
            'success' => true
        ]);

        return redirect($post->specificResourcePath());
    }

    public function edit(Comment $comment)
    {
        return view('comment-edit', [
            'comment' => $comment
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        $validData = $request->validate([
            'content' => 'required|string'
        ]);

        $comment->update([
            'content' => $validData['content'],
            'approved' => 0
        ]);

        session()->flash('commentResult', [
            'message' => 'Comment updated successfully, it will appear after approval',
            'success' => true
        ]);

        return redirect($comment->post->specificResourcePath());
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        session()->flash('commentResult', [
            'message' => 'Comment deleted successfully',
            'success' => true
        ]);

        return redirect($comment->post->specificResourcePath()); 
    }

    public function approve(Comment $comment)
    {
        return $comment->update(['approved' => true]);
    }

    public function undoApproval(Comment $comment)
    {
        return $comment->update(['approved' => false]);
    }
}
