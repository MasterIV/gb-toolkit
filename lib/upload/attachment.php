<?php

class upload_attachment extends upload_img {
	private $data;

	public function attach( $entityType, $entityId ) {
		$this->data = array(
			'category' => $entityType,
			'value' => $entityId,
			'name' => $this->origName
		);
	}

	private function storeData( $path ) {
		db()->insert( 'content_upload',
			array_merge( $this->data, array( 'path' => $path )), 'REPLACE'
		);
	}

	public function saveType($path, $extenstion = null) {
		parent::saveType($path, $extenstion);
		$this->storeData( $path.'.'.$extenstion );
		return $this;
	}

	public function savePng($path) {
		parent::savePng($path);
		$this->storeData($path);
		return $this;
	}

	public function saveJpeg($path) {
		parent::saveJpeg($path);
		$this->storeData($path);
		return $this;
	}

	public function saveGif($path) {
		parent::saveGif($path);
		$this->storeData($path);
		return $this;
	}
}
