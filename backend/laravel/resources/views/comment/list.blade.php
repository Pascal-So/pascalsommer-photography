<ul>
    @foreach($comments as $comment)

        <li class="comment" id="comment_{{ $comment->id }}">
            <h2>
                <a href="{{ $comment->photo->url() }}#comment_{{ $comment->id }}">
                    {{ $comment->name }}
                </a>
            </h2>
            @auth
                @if(isset($admin_overview) && $admin_overview)
                    <p>On a photo in <a href="{{ $comment->photo->url() }}">{{ $comment->photo->post->title }}</a></p>
                @endif
            @endauth
            <p>{{ $comment->created_at->format('Y-m-d') }}</p>
            <p>{!! $comment->commentHTML() !!}</p>

            @auth
                <a href="{{ route('deleteComment', compact('comment')) }}"
                    class="btn btn_comment_delete"
                    data-deletable-comment
                    data-name="{{ str_replace('"', "'", $comment->name) }}"
                    data-comment="{{ str_replace('"', "'", $comment->comment) }}"
                    >
                    Delete
                </a>
            @endauth
        </li>
        <br>

    @endforeach
</ul>
