<?php

class upload_file {
	protected $origName;
	protected $tempName;
	protected $fileSize;
	protected $extension;

	public function __construct( $file ) {
		$this->origName = $file['name'];
		$this->tempName = $file['tmp_name'];
		$this->fileSize = $file['size'];

		if( $pos = strrpos( $file['name'], '.' ))
			$this->extension = strtolower(substr( $file['name'], 1+$pos ));
	}

	public function restrictType( array $types ) {
		if( !in_array( $this->extension, $types ))
			throw new Exception( 'Invalid File Type: '.$this->extenstion );
		return $this;
	}

	public function restrictFileSize( $bytes ) {
		if( $this->fileSize > $bytes )
			throw new Exception( 'Maximum file Size of '.$bytes.' exceeded' );
		return $this;
	}

	public function save( $path ) {
		return $this->saveType( $path, $this->extension );
	}

	public function saveType( $path, $extenstion = null ) {
		$destination = $path.'.'.$extenstion;
		if( !move_uploaded_file( $this->tempName, $destination ))
			throw new Exception('Upload failed');
		return $this;
	}
}
