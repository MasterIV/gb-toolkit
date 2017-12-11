<?php

class upload_list {
	private $category;

	public function __construct( $entityType ) {
		$this->category = $entityType;
	}

	public function get( $entityId, $pattern = '%' ) {
		return db()->query("SELECT * FROM `content_upload`
			WHERE `category` = '%s' AND `value` = '%s' AND `path` LIKE '%s'",
			$this->category, $entityId, $pattern );
	}

	public function getAll() {
		return db()->query("SELECT * FROM `content_upload`
					WHERE `category` = '%s'",
					$this->category );
	}

	public static function delete( $id ) {
		db()->id_del( 'content_upload', $id );
	}

	public function deleteEntity( $entityId, $pattern = '%' ) {
		db()->query( "DELETE FROM `content_upload`
			WHERE `category` = '%s' AND `value` = '%s' AND `path` LIKE '%s'",
			$this->category, $entityId, $pattern );
	}

	public function deleteAll( $pattern = '%' ) {
		db()->query( "DELETE FROM `content_upload`
			WHERE `category` = '%s' AND `path` LIKE '%s'",
			$this->category, $pattern );
	}

	public static function move( $imgId, $to ) {
		db()->id_update( 'content_upload', array( 'value' => $to ), $imgId );
	}
}
