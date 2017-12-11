<?php


class rights_scripts extends rights_abstract {
	public function keys() {
		$result = array();
		foreach( iv::get('scripts') as $key => $script )
			$result[$key] = $script['name'];
		return $result;
	}
}
