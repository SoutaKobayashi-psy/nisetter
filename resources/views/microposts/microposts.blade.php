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
                    <div class="show-content d-flex justify-content-between align-items-center {{ Auth::id() === $micropost->user_id ? "own-post" : "" }}">
                        {{-- 投稿内容 --}}
                        <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                        @if (Auth::id() === $micropost->user_id)
                            <div class="dropdown">
                                <button type="button" id="dropdown1"
                                        class="btn btn-secondary dropdown-toggle btn-sm"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                オプション 
                                </button>
                                <div class="dropdown-menu align-items-center" aria-labelledby="dropdown1">
                                    <span class="dropdown-item-text micropost-edit">編集する</span>
                                        {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                                            {!! Form::submit('消去する', ['class' => 'dropdown-item-text border border-0 bg-white']) !!}
                                        {!! Form::close() !!}
                                </div>
                            </div>
                        @endif
                    </div>
                    @if (Auth::id() === $micropost->user_id)
                    <div class="edit-content-form">
                        {{-- 投稿内容を編集する --}}
                        {!! Form::model($micropost, ['route' => ['microposts.update', $micropost->id], 'method' => 'put' ]) !!}
                            {!! Form::textarea('content', old('content'), ['class' => 'form-control']) !!}
                        <div class="d-flex">
                            <span class="btn btn-light mr-3 edit-cancel">キャンセル</span>
                            <span class="btn btn-primary micropost-update-submit">変更を保存する</span>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    @endif
                    <div class="d-flex align-items-center">
                        <i class="fas fa-heart"></i><span class="ml-1 mr-3">{{ $micropost->favorite_users_count }}</span>
                        @include('favorites.favorite_button')
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $microposts->links() }}
@endif
