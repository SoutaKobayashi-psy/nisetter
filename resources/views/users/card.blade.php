<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $user->name }}</h3>
    </div>
    <div class="card-body">
        {{-- ユーザのメールアドレスをもとにGravatarを取得して表示 --}}
        <img class="rounded img-fluid" src="{{ Gravatar::get($user->email, ['size' => 500]) }}" alt="">
        <div class="mt-2">
            <span class="mt-2">本サービスは<a href="https://ja.gravatar.com/">Gravatar</a>と連携しています</span>
            <span class="mt-2">Gravatarで登録した画像が表示されます</span>
        </div>
    </div>
</div>
{{-- フォロー／アンフォローボタン --}}
@include('user_follow.follow_button')
