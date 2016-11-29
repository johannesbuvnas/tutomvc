<?php

	namespace tutomvc\wp\imagesize;

	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\imagesize\controller\ImageSizeNamesChooseFilter;
	use tutomvc\wp\imagesize\model\ImageSizeProxy;

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