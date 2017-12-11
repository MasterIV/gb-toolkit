<?php

class template_filter_bbcode {
	public static function bbcode2html($string, $nl2br = false) {
		$patterns = array(
			'`\[b\](.+?)\[/b\]`is',
			'`\[i\](.+?)\[/i\]`is',
			'`\[u\](.+?)\[/u\]`is',
			'`\[strike\](.+?)\[/strike\]`is',
			'`\[color=#([0-9A-F]{6})\](.+?)\[/color\]`is',
			'`\[email\](.+?)\[/email\]`is',
			'`\[img\](.+?)\[/img\]`is',
			'`\[url=([a-z0-9]+://)([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\](.*?)\[/url\]`si',
			'`\[url\]([a-z0-9]+?://){1}([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)\[/url\]`si',
			'`\[url\]((www|ftp)\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\[/url\]`si',
			'`\[code](.+?)\[/code\]`is'
		);

		$replaces =  array(
			'<strong>\\1</strong>',
			'<em>\\1</em>',
			'<span style="border-bottom: 1px solid black;">\\1</span>',
			'<strike>\\1</strike>',
			'<span style="color:#\1;">\2</span>',
			'<a href="mailto:\1">\1</a>',
			'<img src="\1" alt="" style="border:0px;" />',
			'<a target="_blank" href="\1\2">\6</a>',
			'<a target="_blank" href="\1\2">\1\2</a>',
			'<a target="_blank" href="http://\1">\1</a>',
			'<pre>\\1</pre>'
		);

		$result = preg_replace($patterns, $replaces, $string);
		$result = preg_replace_callback('`((href|src)="|)(https?://[^\s<>"]+)`i', 'template_filter_bbcode::replaceUrl', $result);


		if($nl2br) {
			return nl2br($result);
		} else {
			return $result;
		}
	}

	protected static function replaceUrl($matches) {
		return $matches[1] ? $matches[0] : '<a target="_blank" href="'.$matches[3].'">'.$matches[3].'</a>';
	}
} 
