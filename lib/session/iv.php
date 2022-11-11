<?php

class session_iv {
	protected $name;
	protected $cookie_lifetime = 604800;

	/**
	 * Constructor starts a Session
	 * @param string $name Session Name
	 */
	public function __construct( $name = 'IVSESSID' ) {
		session_name( $this->name = $name );
		session_start();
	}

	/**
	 * Preformes relogin based on cookie data
	 * @param string $key
	 * @return boolean
	 */
	public function relogin( $flag = 0 ) {
		if( $this->user && ($this->flags & $flag)) return false;
		if( empty( $_COOKIE[$this->name."_ID"] )) return false;
		if(( $_COOKIE[$this->name."_FLAG"] & $flag ) != $flag ) return false;

		$id = $_COOKIE[$this->name."_ID"];
		$key = $_COOKIE[$this->name."_KEY"];

		if( !$user = db()->user_data->id( $id )) return $this->killCookie();
		if( self::transform( $key, $user->pass_salt, $user->pass_format ) != $user->pass_hash ) return $this->killCookie();
		if(( $user->type & $flag ) != $flag ) return false;

		$this->setUser($user->id, $flag);

		return $user;
	}

	/**
	 * Performes login
	 * @param string $name
	 * @param string $pass
	 * @param boolean $relogin
	 * @param int $flag
	 * @return boolean
	 */
	public function login( $name, $pass, $relogin = false, $flag = 0 ) {
		if( !$user = db()->user_data->name( $name )) return false;
		if( self::crypt( $pass, $user->pass_salt, $user->pass_format ) != $user->pass_hash ) return false;
		if(( $user->type & $flag ) != $flag ) return false;

		$this->setUser($user->id, $flag);

		if( $relogin ) {
			$lifetime = time() + $this->cookie_lifetime;
			setcookie( $this->name."_ID", $user->id, $lifetime );
			setcookie( $this->name."_KEY", self::loginKey( $pass, $user->pass_salt, $user->pass_format ), $lifetime );
			setcookie( $this->name."_FLAG", $user->type, $lifetime );
		}

		return $user;
	}

	/**
	 * Set the current user for this session
	 * @param int $id user id
	 * @param int $flag access bit mask
	 */
	private function setUser( $id, $flag ) {
		if( $this->user == $id ) {
			$this->flags = $this->flags | $flag;
			$this->ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$this->user = $id;
			$this->flags = $flag;
			$this->ip = $_SERVER['REMOTE_ADDR'];
		}

		db()->user_data->updateRow( array('last_login' => time()), $id );
	}

	/**
	 * Deletes relogin Cookies
	 * @return boolean false
	 */
	protected function killCookie() {
		$lifetime = time() -1;
		setcookie( $this->name."_ID", 0, $lifetime );
		setcookie( $this->name."_KEY", 0, $lifetime );
		return false;
	}

	/**
	 * Terminates a Session
	 */
	public function logout() {
		session_unset();
		$this->killCookie();
	}

	/**
	 * Checks if user ist logged in and returns user Obejct otherwise false
	 * @param int $flag
	 * @return mixed
	 */
	public function user( $flag = 0 ) {
		if( !$this->user ) return false;
		if(( $this->flags & $flag ) != $flag ) return false;
		if( $this->ip != $_SERVER['REMOTE_ADDR'] ) return false;
		if( !$user = db()->user_data->id( $this->user )) return false;
		if(( $user->type & $flag ) != $flag ) return false;

		db()->user_data->updateRow( array(
			'last_refresh' => time(),
			'last_ip' => $_SERVER['REMOTE_ADDR']
		), $user->id );

		return $user;
	}

	public function flag( $flag ) {
		$this->flags = $this->flags ^ $flag;
	}

	/**
	 * Reads a session variable
	 * @param string $varname
	 * @return mixed
	 */
	public function __get($varname) {
		return $_SESSION[$varname];
	}

	/**
	 * Set as session variable
	 * @param string $varname
	 * @param mixed $value
	 */
	public function __set($varname, $value) {
		$_SESSION[$varname] = $value;
	}

	/**
	 * Erzeugt einen login key und transformiert diesen in den passwiord hash
	 * @param string $string
	 * @param string $salt
	 * @param int $type
	 * @return string
	 */
	public static function crypt( $string, $salt, $type = 0 ) {
		return self::transform( self::loginKey( $string, $salt, $type), $salt, $type );
	}

	/**
	 * transformirt den login key in den passwort hash
	 * @param string $string
	 * @param string $salt
	 * @return string
	 */
	protected static function transform( $string, $salt, $type = 0 ) {
		switch( $type ) {
			case 1: return sha1( $salt.$string );
			default: return md5( $salt.$string );
		}
	}

	/**
	 * erzeugt einen login key
	 * @param string $string
	 * @param string $salt
	 * @return string
	 */
	protected static function loginKey( $string, $salt, $type = 0 ) {
		switch( $type ) {
			case 1: return sha1( $salt.sha1( $string ));;
			default: return md5( $string.$salt );
		}
	}

	/**
	 * This method is evil and should only avoid copy & paste
	 */
	public function changePassword( $action ) {
		$result = array();

		if( !empty( $_POST['change_pass'])) {
			if( $_POST['change_pass'] != $_POST['change_repeat'] ) {
				$result['error'] = 'Passwort und Wiederholung stimmen nicht überein.';
			} else {
				$pass = self::crypt($_POST['change_pass'], $salt = uniqid());
				db()->user_data->updateRow( array('pass_format' => 0, 'pass_hash' => $pass, 'pass_salt' => $salt), userid());
				$result['success'] = 'Passwort erfolgreich geändert.';
			}
		}

		$result['form'] = $form = new form_renderer( $action );
		$form->password( 'change_pass', 'Neues Passwort' );
		$form->password( 'change_repeat', 'Wiederholung' );

		return $result;
	}
}
