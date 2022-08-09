<?php

	namespace TutoMVC\WordPress\Modules\ImageSize\Model;

	use TutoMVC\Model\Simple\NameObject;

	class ImageSizeVO extends NameObject
	{
		private $_title;
		private $_width;
		private $_height;
		private $_crop;

		function __construct( $name, $title, $width, $height, $crop = FALSE )
		{
			parent::__construct( $name );
			$this->setTitle( $title );
			$this->setWidth( $width );
			$this->setHeight( $height );
			$this->setCrop( $crop );
		}

		/* SET AND GET */
		public function setTitle( $title )
		{
			$this->_title = $title;
		}

		public function getTitle()
		{
			return $this->_title;
		}

		public function setWidth( $width )
		{
			$this->_width = $width;
		}

		public function getWidth()
		{
			return $this->_width;
		}

		public function setHeight( $height )
		{
			$this->_height = $height;
		}

		public function getHeight()
		{
			return $this->_height;
		}

		public function setCrop( $crop )
		{
			$this->_crop = $crop;
		}

		public function getCrop()
		{
			return $this->_crop;
		}
	}
