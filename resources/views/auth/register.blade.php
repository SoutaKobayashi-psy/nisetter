@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h1>{{ Request::routeIs('users.edit') ? 'ユーザー情報編集' : '新規登録' }}</h1>
    </div>

    <div class="row">
        <div class="col-sm-6 offset-sm-3">
             @if (Request::routeIs('users.edit'))
                 {!! Form::open(['route' => ['users.update', Auth::id()], 'method' => 'put']) !!}
             @else
                 {!! Form::open(['route' => 'signup.post']) !!}
             @endif
            
                <div class="form-group">
                    {!! Form::label('name', '氏名(アカウント名)') !!}
                    {!! Form::text('name', old('name', empty($user->name) ? '' : $user->name), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('email', 'メールアドレス') !!}
                    {!! Form::email('email', old('email', empty($user->email) ? '' : $user->email), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password', 'パスワード') !!}
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password_confirmation', '再入力') !!}
                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                </div>

             @if (Request::routeIs('users.edit'))
                <div class="d-flex">
                    {{ link_to_route('users.show', 'キャンセル', ['user' => Auth::id()], ['class' => 'btn btn-light mr-3'] ) }}
                    {!! Form::submit('更新する', ['class' => 'btn btn-primary']) !!}
                </div>
             @else
                {!! Form::submit('新規登録', ['class' => 'btn btn-primary btn-block']) !!}
             @endif
            {!! Form::close() !!}
        </div>
    </div>

@endsection
