$(function() {
	var $items = $('.dropdown-item-text');
	for (var i = 0; i <= $items.length -1; i++) {
		$($items[i]).click(function() {
console.log('test');
		});
	}
	///	var value = $(this);
/*
		var $otherText = $("#statusOtherText");

		if (value == 3) {
			$otherText.prop("disabled", false);
		} else {
			$otherText.prop("disabled", true);
			$otherText.val("");
		}
*/
});
