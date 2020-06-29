    @if (Auth::user()->is_favorite($micropost->id))
        {{-- unfavoriteボタンのフォーム --}}
        {!! Form::open(['route' => ['microposts.unfavorite', $micropost->id], 'method' => 'delete']) !!}
            {!! Form::submit('お気に入りから外す', ['class' => "btn btn-danger btn-sm mr-1"]) !!}
        {!! Form::close() !!}
    @else
        {{-- favoriteボタンのフォーム --}}
        {!! Form::open(['route' => ['microposts.favorite', $micropost->id]]) !!}
            {!! Form::submit('お気に入り', ['class' => "btn btn-primary btn-sm mr-1"]) !!}
        {!! Form::close() !!}
    @endif
