<?php

class template_filter_gravatar {
	/**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param int $s Size in pixels, defaults to 80px [ 1 - 2048 ]
	 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
	 * @return string containing either just a URL or a complete image tag
	 * @source http://gravatar.com/site/implement/images/php/
	 */
	public static function gravatar( $email, $s = 80, $d = 'mm' ) {
		$url = 'http://www.gravatar.com/avatar/'.md5( strtolower( trim( $email ) ) )."?s=$s&d=$d";
		return  '<img src="' . $url . '" width="'.$s.'" height="'.$s.'" alt="Avatar" />';
	}
}