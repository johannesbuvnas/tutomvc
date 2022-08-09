<?php

	namespace TutoMVC\WordPress\Modules\ImageSize;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\ImageSize\Controller\Filter\ImageSizeNamesChooseFilter;
	use TutoMVC\WordPress\Modules\ImageSize\Model\ImageSizeProxy;

	class ImageSizeModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.imagesize";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		protected function prepModel()
		{
			$this->registerProxy( new ImageSizeProxy( ImageSizeProxy::NAME ) );
		}

		protected function prepController()
		{
			$this->registerCommand( "image_size_names_choose", new ImageSizeNamesChooseFilter() );
		}
	}
