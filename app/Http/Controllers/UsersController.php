<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Micropost;

class UsersController extends Controller
{
    public function index()
    {
        // ユーザー一覧をidの降順で取得
        $users = User::orderBy('id', 'desc')->paginate(10);

        // ユーザー一覧ビューでそれを表示
        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function show($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーの投稿一覧を作成日時の降順で取得
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);

        // お気に入り数をカウントする
        foreach ($microposts as $key => $micropost) {
            $microposts[$key]->loadRelationshipCounts();
        }

        // ユーザー詳細ビューでそれを表示
        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }

    /*
     * ユーザー情報の編集ページを表示するアクション
     *
     * @param $id ユーザのid
     * @returnn \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // ユーザー一覧ビューでそれを表示
        return view('auth.register', [
            'user' => $user,
        ]);
    }

    /*
     * ユーザー情報を更新するアクション
     *
     * @param $id ユーザのid
     * @returnn \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'. \Auth::id(),
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => ':attributeを入力してください',
            'name.string' => ':attributeは文字列で入力してください',
            'name.max' => ':attributeは255文字以内で入力してください',
            'email.required' => ':attributeを入力してください',
            'email.string' => ':attributeの表記が正しくありません',
            'email.email' => ':attributeの表記が正しくありません',
            'email.max' => ':attributeは255文字以内で入力してください',
            'email.unique' => 'すでにその:attributeは使用されています',
            'password.required' => ':attributeを入力してください',
            'password.max' => ':attributeは255文字以内で入力してください',
            'password.min' => ':attributeは8文字以上で入力してください',
            'password.confirmed' => '再入力用の:attributeが一致しません',
        ], [
            'name' => '氏名',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
        ]);

        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // ユーザー情報更新
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /*
     * ユーザーのフォロー一覧ページを表示するアクション
     *
     * @param $id ユーザのid
     * @returnn \Illuminate\Http\Response
     */
    public function followings($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);

        // フォロー一覧ビューでそれらを表示
        return view('users.followings', [
            'user' => $user,
            'users' => $followings,
        ]);
    }

    /*
     * ユーザーのフォロワー一覧ページを表示するアクション
     *
     * @param $id ユーザのid
     * @returnn \Illuminate\Http\Response
     */
    public function followers($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのフォロワー一覧を取得
        $followers = $user->followers()->paginate(10);

        // フォロー一覧ビューでそれらを表示
        return view('users.followers', [
            'user' => $user,
            'users' => $followers,
        ]);
    }

    /*
     * ユーザーのお気にいり一覧ページを表示するアクション
     *
     * @param $id ユーザのid
     * @returnn \Illuminate\Http\Response
     */
    public function favorites($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのお気に入り一覧を取得
        $microposts = $user->favorites()->paginate(10);

        // お気に入り数をカウントする
        foreach ($microposts as $key => $micropost) {
            $microposts[$key]->loadRelationshipCounts();
        }

        // フォロー一覧ビューでそれらを表示
        return view('users.favorites', [
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }
}
