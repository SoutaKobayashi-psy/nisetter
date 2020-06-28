<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * 投稿をお気にいりするアクション
     *
     * @param $id micropostのid
     * @return \Illminate\Http\Response
     */
    public function store($id)
    {
        // 認証済ユーザー(閲覧者)が、idの投稿をお気に入りする
        \Auth::user()->favorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * 投稿のお気に入りを外すアクション
     *
     * @param $id micropostのid
     * @return \Illminate\Http\Response
     */
    public function destroy($id)
    {
        // 認証済ユーザー(閲覧者)が、idの投稿をお気に入りから外す
        \Auth::user()->unfavorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
}
