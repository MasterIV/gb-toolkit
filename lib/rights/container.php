<?php

class rights_container {
	private $rights = array();
	private $sysadmin = false;

	public $providers = array();

	private function addRights( $rights ) {
		foreach( $rights as $type => $r ) {
			foreach( $r as $element => $perms )
				if( is_array($perms) && is_array( $this->rights[$type][$element] ))
					$this->rights[$type][$element] = array_merge( $this->rights[$type][$element], $perms );
				elseif( is_array($perms) || empty( $this->rights[$type][$element] ))
					$this->rights[$type][$element] = $perms;
		}
	}

	public function __construct( $user, $sysadmin ) {
		$rights = db()->query("SELECT g.rights FROM user_group_owner go
			JOIN user_groups g on go.`group` = g.id WHERE go.user = %d
			AND go.start_date < %d
			AND ( go.end_date IS NULL OR go.end_date > %d)", $user, time(), time());

		foreach( $rights as $r )
			if( $r = unserialize($r['rights']))
				$this->addRights($r);

		foreach( iv::get('rights') as $type => $provider ) {
			$class = literal($provider['provider']);
			$this->providers[$type] = new $class($provider['arguments'], $provider['always']);
			$this->providers[$type]->name = literal($provider['caption']);
		}

		$this->sysadmin = $sysadmin;
	}

	public function has( $type, $key ) {
		return $this->sysadmin || isset( $this->rights[$type][$key] )
						|| isset( $this->providers[$type]->always[$key] );
	}

	public function flags( $type, $key ) {
		if( !$this->has( $type, $key ) || empty( $this->providers[$type] )) {
			return array();
		} else {
			return $this->providers[$type]->flags($key, $this->rights[$type][$key], $this->sysadmin);
		}
	}
}
