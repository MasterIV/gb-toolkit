<?php

class update_migration {
	public function install( $file ) {
		$this->apply( $file, 'install' );
	}

	public function remove( $file ) {
		$this->apply( $file, 'remove' );
	}

	private function apply( $file, $type ) {
		require 'migration/'.$file;
		if( $type == 'remove' ) remove(); else install();
		db()->update_migration->insert(array( 'id' => $file ));
	}

	public function create( $file ) {
		$fileName = 'migration/'.date('Ymd-Hi-').$file.'.php';
		file_put_contents( $fileName, "<?php\n\nfunction install() {\n\tdb()->query(\"\");\n}\n\nfunction remove() {\n\tdb()->query(\"\");\n}\n" );
		chmod($fileName, 0777);
	}

	public static function listPending() {
		$all = globFiles('migration/*');
		$applied = db()->select('update_migration')->assocs('id');
		$pending =array();

		foreach( $all as $id )
			if( empty( $applied[$id] ))
				$pending[] = $id;

		return $pending;
	}
}
