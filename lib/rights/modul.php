<?php

class rights_modul extends rights_abstract {
	public function keys() {
		$menu = $result = array();

		foreach( iv::get('moduls') as $mod )
			$menu[$mod['file']] = $mod['name'];

		foreach(glob('moduls/*.php') as $file ) {
			$key = substr( $file, 7, -4 );
			$name = $menu[$key] ?: $key;
			$result[$key] =  $name;
		}

		return $result;
	}

	public function flags( $key, $value, $sysadmin ) {
		$result = array();

		if( isset( $this->arguments[$key] ))
			foreach( $this->arguments[$key] as $flag => $caption )
				$result[$flag] = isset( $value[$flag] ) || $sysadmin;

		return $result;
	}

	public function flagNames( $key ) {
		return $this->arguments[$key] ?: array();
	}
}

