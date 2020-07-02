<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Micropost;

class MicropostsController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            // 認証済みユーザーを取得
            $user = \Auth::user();
            // ユーザーとフォロー中ユーザーの投稿の一覧を作成日時の降順で取得
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);

            // お気に入り数をカウントする
            foreach ($microposts as $key => $micropost) {
                $microposts[$key]->loadRelationshipCounts();
            }

            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
        }

        // welocomeビューでそれらを表示
        return view('welcome', $data);
    }

    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'content' => 'required|max:255',
        ], [
            'content.required' => ':attributeを入力してください',
            'content.max'  => ':attributeは255文字以内で入力してください',
        ], [
            'content' => 'つぶやき',
        ]);

        // 認証済ユーザー(閲覧者)の投稿として作成(リクエストされた値をもとに作成)
        $request->user()->microposts()->create([
            'content' => $request->content,
        ]);

        // 前のURLにリダイレクトさせる
        return back();
    }

    public function update(Request $request, $Id)
    {
        // バリデーション
        $request->validate([
            'content' => 'required|max:255',
        ], [
            'content.required' => ':attributeを入力してください',
            'content.max'  => ':attributeは255文字以内で入力してください',
        ], [
            'content' => 'つぶやき',
        ]);

        // idの値で投稿を検索して取得
        $micropost = Micropost::findOrFail($Id);
        // 投稿を更新
        $micropost->content = $request->content;
        $micropost->save();

        // トップページへリダイレクトさせる
        return back();
    }

    public function destroy($id)
    {
        // idの値で投稿を検索して取得
        $micropost = \App\Micropost::findOrFail($id);

        // 認証済ユーザー(閲覧者)がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $micropost->user_id) {
            $micropost->delete();
        }

        // 前のURLへリダイレクトさせる
        return back();
    }
}
