<?php

class rights_forum extends rights_abstract {
	public function keys() {
		return db()->query("SELECT id, name
			FROM forum_board
			WHERE !public_read OR !public_write OR !public_reply")->relate();
	}

	public function flags( $key, $value, $sysadmin ) {
		return array(
			'write' => isset( $value['write'] ) || $sysadmin,
			'reply' => isset( $value['reply'] ) || $sysadmin,
			'moderate' => isset( $value['moderate'] ) || $sysadmin,
		);
	}

	public function flagNames( $key ) {
		$board = db()->forum_board->row($key)->assoc();
		$result = array('moderate' => 'Moderieren');

		if( !$board['public_write'] ) $result['write'] = 'Schreiben';
		if( !$board['public_reply'] ) $result['reply'] = 'Antworten';

		return $result;
	}
}