@if (count($microposts) > 0)
    <ul class="list-unstyled">
        @foreach ($microposts as $micropost)
            <li class="media mb-3">
                {{-- 投稿の所有者のメールアドレスをもとにgravatarを取得して表示 --}}
                <img class="mr-2 rouded" src="{{ Gravatar::get($micropost->user->email, ['size' => 50]) }}" alt="">
                <div class="media-body">
                    <div>
                        {{-- 投稿の所有者のユーザー詳細ページへのリンク --}}
                        {!! link_to_route('users.show', $micropost->user->name, ['user' => $micropost->user->id]) !!}
                        <span class="text-muted">{{ $micropost->created_at }}</span>
                    </div>
                    <div class="show-content d-flex justify-content-between align-items-center">
                        {{-- 投稿内容 --}}
                        <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                        <div class="dropdown">
                            <button type="button" id="dropdown1"
                                    class="btn btn-secondary dropdown-toggle btn-sm"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                            オプション 
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdown1">
                                <span class="dropdown-item-text">編集する(実装中)</span>
                            </div>
                        </div>
                    </div>
                    <div class="edit-content-form" hidden>
                        {{-- 投稿内容を編集する --}}
                        {!! Form::model($micropost, ['route' => ['microposts.update', $micropost->id], 'method' => 'put' ]) !!}
                            {!! Form::textarea('content', old('content'), ['class' => 'form-control']) !!}
                        {!! Form::submit('変更を保存する', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-heart"></i><span class="ml-1 mr-3">{{ $micropost->favorite_users_count }}</span>
                        @include('favorites.favorite_button')
                        @if (Auth::id() === $micropost->user_id)
                            {{-- 投稿削除ボタン --}}
                            {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                                {!! Form::submit('消去する', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $microposts->links() }}
@endif
