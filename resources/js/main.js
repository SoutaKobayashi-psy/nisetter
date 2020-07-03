$(function() {
	// 編集ボタン
	var $items = $('.micropost-edit');
	// 編集フォーム
	var $microposts_form = $('.edit-content-form');
	// 投稿内容テキスト
	var $microposts_text = $('.show-content.own-post');
	// 編集キャンセルボタン
	var $cancel = $('.edit-cancel');

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

	// 投稿のアップデート
	var $update_submit = $('.micropost-update-submit');
	// クリックされたボタンから投稿元を割り出す
	$($update_submit).each(function(i,ele) {
		$(ele).click(function() {
			$.ajax({
				type:'POST', //GETかPOSTか
				url:$($update_submit[i]).closest('form').attr('action'), //url+ファイル名 .htmlは省略可
				data: {
					'_method': 'PUT',
					'_token': $($update_submit[i]).parent().parent().find('input[name=_token]').val(),
					'content': $($update_submit[i]).parent().parent().find('textarea[name=content]').val(),
				},
				dataType:'html', //他にjsonとか選べるとのこと
			}).done(function (results){
				// 表示テキストを更新後に上書き
				var newcontent = $($update_submit[i]).parent().parent().find('textarea[name=content]').val();
				$($update_submit[i]).parents('.media-body').find('.show-content p').text(newcontent);
				// 編集フォームを閉じる
				$($update_submit[i]).parents('.media-body').find('.edit-content-form').hide();
				$($update_submit[i]).parents('.media-body').find('.show-content').addClass('d-flex').show();
			}).fail(function(){
				console.log("ajax通信に失敗しました");
			});
		});
	});
});
