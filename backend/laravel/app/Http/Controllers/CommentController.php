<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Photo;
use App\Comment;
use App\Blacklist;
use App\Rules\NoHTML;
use App\Util;

class CommentController extends Controller
{
    private function logComment(string $name, string $comment, Photo $photo, $log_to_telegram=false, $blocked=false)
    {
        $post_title = Util::escapeMarkdown($photo->isPublic() ? $photo->post->title : 'unpublished');
        $name = Util::escapeMarkdown($name);
        $comment = Util::escapeMarkdown($comment);
        $photo_id = $photo->id;

        if ($log_to_telegram) {
            $message = "*{$name}* in \"{$post_title}\" \\- {$photo_id}\n\n{$comment}";
            Log::channel('telegram_comments_channel')->info($message);
        }

        $json = json_encode(compact('name', 'comment', 'post_title', 'photo_id'),
                            JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        Log::info(($blocked ? 'Blocked comment: ' : 'New comment: ') . $json);
    }

    public function postComment(Photo $photo, Request $request)
    {
        // when validating incoming comments, we only validate basic stuff first,
        // and then scan the blacklist before doing further validation. This is
        // because we want the requests that are definitely spam to not get any
        // error messages, but other stuff that contains html or so can get an
        // error message back.

        $back_url = $photo->url() . '#comment-form';

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:' . strval(config('constants.max_comment_author_length')),
            'comment' => 'required|max:' . strval(config('constants.max_comment_length')),
        ]);

        if ($validator->fails()) {
            return redirect($back_url)
                        ->withErrors($validator)
                        ->withInput();
        }


        if (!Blacklist::checkComment($request->comment)) {
            $this->logComment($request->name, $request->comment, $photo, false, true);

            return redirect($photo->url());
        }

        $validator = Validator::make($request->all(), [
            'name' => [new NoHTML],
            'comment' => [new NoHTML],
        ]);

        if ($validator->fails()) {
            $this->logComment($request->name, $request->comment, $photo, false, true);

            return redirect($back_url)
                        ->withErrors($validator)
                        ->withInput();
        }

        $photo->comments()->create($request->only(['name', 'comment']));

        $this->logComment($request->name, $request->comment, $photo, true);

        return redirect($photo->url());
    }

    public function delete(Comment $comment)
    {
        $comment->delete();

        return back();
    }

    public function adminIndex()
    {
        $comments = Comment::latest()->get();

        return view('comment.index', compact('comments'));
    }
}
