<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tobias.rojahn
 * Date: 03.07.13
 * Time: 14:54
 * To change this template use File | Settings | File Templates.
 */

class upload_img extends upload_file {
	private $img;
	private $width = 0;
	private $height = 0;

	public  function __construct( $file ) {
		parent::__construct( $file );

		switch( $this->extension ) {
			case 'png':
				$this->img = imagecreatefrompng( $this->tempName ); break;
			case 'gif':
				$this->img = imagecreatefromgif( $this->tempName ); break;
			case 'jpg': case 'jpeg':
				$this->img = imagecreatefromjpeg( $this->tempName ); break;
		}

		if( $this->img ) {
			$this->width = imagesx( $this->img );
			$this->height = imagesy( $this->img );
		}
	}

	public function restrictWidth( $width ) {
		if( $this->width > $width )
			throw new Exception( 'Image must have a width greater than '.$width);
		return $this;
	}

	public function restrictHeight( $height ){
		if( $this->height > $height )
			throw new Exception( 'Image must have a height greater than '.$height);
		return $this;
	}

	public function restrictImageSize( $width, $height ) {
		return $this->restrictWidth( $width )
				->resizeByHeight( $height );
	}

	public function resizeStretch( $width, $height ) {
		if( !$this->img ) throw new Exception('Not a valid image!');

		$new = imagecreatetruecolor( $width, $height );
		imagealphablending( $new , false );
		imagecopyresampled( $new, $this->img,
			0, 0, 0, 0, $width, $height, $this->width, $this->height );
		imagedestroy( $this->img );

		$this->img = $new;
		$this->width = $width;
		$this->height = $height;
		return $this;
	}

	public function resizeScale( $width, $height ) {
		$factor = min( $width / $this->width, $height / $this->height );
		$height = $this->height * $factor;
		$width = $this->width * $factor;
		return $this->resizeStretch( $width, $height );
	}

	public function resizeByWidth( $width ) {
		$height = $this->height * $width / $this->width;
		return $this->resizeStretch( $width, $height );
	}

	public function resizeByHeight( $height ) {
		$width = $this->width * $height / $this->height;
		return $this->resizeStretch( $width, $height );
	}

	public function savePng( $path ) {
		if( !$this->img ) throw new Exception('Not a valid image!');
		imagepng( $this->img, $path.'.png' );
		return $this;
	}

	public function saveJpeg( $path ) {
		if( !$this->img ) throw new Exception('Not a valid image!');
		imagejpeg( $this->img, $path.'.jpeg' );
		return $this;
	}

	public function saveGif( $path ) {
		if( !$this->img ) throw new Exception('Not a valid image!');
		imagegif( $this->img, $path.'.gif' );
		return $this;
	}
}
