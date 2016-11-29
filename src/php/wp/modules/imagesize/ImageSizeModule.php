<?php

	namespace tutomvc\wp\imagesize;

	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\imagesize\model\ImageSizeProxy;
	use tutomvc\wp\imagesize\model\ImageSizeVO;
	use tutomvc\wp\system\SystemApp;

	class ImageSizeModule
	{
		/**
		 * @param ImageSizeVO $imageSize
		 *
		 * @return ImageSizeVO
		 */
		public static function add( $imageSize )
		{
			return self::getProxy()->add( $imageSize );
		}

		public static function getProxy()
		{
			return self::getFacade()->getProxy( ImageSizeProxy::NAME );
		}

		/**
		 * @return ImageSizeModuleFacade
		 */
		public static function getFacade()
		{
			return Facade::getInstance( ImageSizeModuleFacade::KEY ) ? Facade::getInstance( ImageSizeModuleFacade::KEY ) : SystemApp::getInstance()->registerModule( new ImageSizeModuleFacade() );
		}
	}