<?php

	namespace TutoMVC\WordPress\Modules\ImageSize;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\ImageSize\Model\ImageSizeProxy;
	use TutoMVC\WordPress\Modules\ImageSize\Model\ImageSizeVO;
	use TutoMVC\WordPress\System\SystemApp;

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
