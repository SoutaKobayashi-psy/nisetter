<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * この投稿に関係するモデルの件数をロードする
     */
    public function loadRelationshipCounts()
    {
        $this->loadCount(['favorite_users']);
    }

    /**
     * この投稿をお気に入りしたユーザーを取得する。
     *
     */
    public function favorite_users()
    {
        return $this->belongsToMany(User::class, 'favorites', 'micropost_id', 'user_id');
    }

}
