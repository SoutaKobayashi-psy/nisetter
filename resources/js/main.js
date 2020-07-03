$(function() {
	// 編集ボタン
	var $items = $('.micropost-edit');
	// 編集フォーム
	var $microposts_form = $('.edit-content-form.own-post');
	// 投稿内容テキスト
	var $microposts_text = $('.show-content.own-post');
	// 編集キャンセルボタン
	var $cancel = $('.edit-cancel.own-post');

	// クリックされた投稿編集フォームを表示する
	$($items).each(function(i,ele) {
		$(ele).click(function() {
			$($microposts_form[i]).show();
			$($microposts_text[i]).removeClass('d-flex').hide();
		});
	});

	// キャンセルボタンを押下したとき
	$($cancel).each(function(i,ele) {
		$(ele).click(function() {
			$($microposts_form[i]).hide();
			$($microposts_text[i]).addClass('d-flex').show();
		});
	});
});
