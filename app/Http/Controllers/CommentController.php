<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;
use App\Comment;
use App\Blacklist;
use App\Rules\NoHTML;

class CommentController extends Controller
{
    public function postComment(Photo $photo, Request $request){
        // when validating incoming comments, we only validate basic stuff first,
        // and then scan the blacklist before doing further validation. This is
        // because we want the requests that are definitely spam to not get any
        // error messages, but other stuff that contains html or so can get an
        // error message back.

        $request->validate([
            'name' => 'required|max:255',
            'comment' => 'required',
        ]);

        if(!Blacklist::checkComment($request->comment)){
            return redirect()->route('viewPhoto', compact('photo'));
        }

        $request->validate([
            'name' => [new NoHTML],
            'comment' => ['max:10000', new NoHTML],
        ]);

        if(config('constants.push_notifications')){
            \Simplepush::send(env('SIMPLEPUSH_KEY'), $request->name, $request->comment, 'Comment');
        }

        $photo->comments()->create($request->only(['name', 'comment']));

        return redirect()->route('viewPhoto', compact('photo'));
    }

    public function delete(Comment $comment){
        $comment->delete();

        return back();
    }

    public function adminIndex(){
        $comments = Comment::latest()->get();
        
        return view('comment.index', compact('comments'));
    }
}