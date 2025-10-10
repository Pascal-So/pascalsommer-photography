@auth
    @php
    $links = [
        ['route' => 'home',       'name' => 'Home',     'shortcut' => 72, 'title' => '(shortcut: h)'],
        ['route' => 'photos',     'name' => 'Staging',  'shortcut' => 83, 'title' => '(shortcut: s)'],
        ['route' => 'createPost', 'name' => 'New Post'],
        ['route' => 'posts',      'name' => 'Posts'],
        ['route' => 'tags',       'name' => 'Tags'],
        ['route' => 'comments',   'name' => 'Comments'],
        ['route' => 'stats',      'name' => 'Stats'],
        ['route' => 'gallery',    'name' => 'Gallery',  'shortcut' => 71, 'title' => '(shortcut: g)']
    ];
    @endphp

    <nav class="alignright admin-nav">
        @foreach ($links as $link)
            <a  class="{{ Route::is($link['route']) ? 'nav-link-active' : '' }}"
                href="{{ route($link['route']) }}"

                @if (isset($link['shortcut']))
                    data-shortcutkeycode="{{$link['shortcut']}}"
                @endif

                @if (isset($link['title']))
                    title="{{$link['title']}}"
                @endif
            >
                {{$link['name']}}
            </a>
        @endforeach

        <span>&nbsp;</span>
        <a href="{{ route('logout') }}">Logout</a>
    </nav>
@endauth