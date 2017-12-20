$(window).keypress(function(event) {
	if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)) return true;
	$('#save').click();
	event.preventDefault();
	return false;
});