<p>
    <a title="Soundcloud" href="https://soundcloud.com/pascal-sommer" class="external-platform-link">
        @include('icons.soundcloud')
    </a><a title="Github" href="https://github.com/pascal-so" class="external-platform-link">
        @include('icons.github')
    </a><a class="tag" style="margin-bottom: 0" href="{{ route('filtered', ['tags' => 'Favourites']) }}#start-content">
        Favourites
    </a><a class="tag" style="margin-bottom: 0" href="{{ route('search') }}">
        Search
    </a><a title="Youtube" href="https://youtube.com/pascalsommermovies" class="external-platform-link">
        @include('icons.youtube')
    </a><a title="RSS feed - {{ config('feed.feeds.posts.title') }}" type="application/atom+xml" href="{{ asset('feed') }}" class="external-platform-link">
        @include('icons.rss')
    </a>
</p>