<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * このユーザーが所有する投稿。(Micropostモデルとの関係を定義)
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }

    /**
     * このユーザーに関係するモデルの件数をロードする
     */
    public function loadRelationshipCounts()
    {
        $this->loadCount(['microposts', 'followings', 'followers', 'favorites']);
    }

    /**
     * このユーザーがフォロー中のユーザー。(Userモデルとの関係を定義)
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    /**
     * このユーザーをフォロー中のユーザー。(Userモデルとの関係を定義)
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    /**
     * $userIDで指定されたユーザーをフォローする。
     *
     * @param int $userId
     * @return bool
     */
    public function follow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // 既にフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }

    /**
     * $userIDで指定されたユーザーをアンフォローする。
     *
     * @param int $userId
     * @return bool
     */
    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // 既にフォローしているかつ、自分自身ではない場合フォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローか、自分自身なら何もしない
            return false;
        }
    }

    /**
     * 指定された$userIdのユーザーをこのユーザーがフォロー中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param int $userId
     * @return bool
     */
    public function is_following($userId)
    {
        // フォロー中ユーザーの中に$userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }

    /**
     * このユーザーとフォロー中のユーザーの投稿に絞り込む。
     *
     */
    public function feed_microposts()
    {
        // このユーザーがフォロー中のユーザーのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザーのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザーが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }

    /**
     * このユーザーがお気に入りした投稿を取得する。
     *
     */
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id');
    }

    /**
     * $micropostIdで指定された投稿をお気に入りにする。
     *
     */
    public function favorite($micropostId)
    {
        // 既にお気に入りしていないか確認する
        if (!$this->is_favorite($micropostId)) {
            $this->favorites()->attach($micropostId);
            return true;
        } else {
            return false;
        }
    }

    /**
     * $micropostIdで指定された投稿をお気に入りから外す。
     *
     */
    public function unfavorite($micropostId)
    {
        // 既にお気に入りしていないか確認する
        if ($this->is_favorite($micropostId)) {
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            return false;
        }
    }

    /**
     * $micropostIdで指定された投稿を既にお気に入りしているか確認する。
     *
     */
    public function is_favorite($micropostId)
    {
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }
}
