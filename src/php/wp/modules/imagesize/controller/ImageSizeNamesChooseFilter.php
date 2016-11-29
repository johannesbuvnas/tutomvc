<?php

	namespace tutomvc\wp\imagesize\controller;

	use tutomvc\wp\core\controller\command\FilterCommand;
	use tutomvc\wp\imagesize\ImageSizeModule;
	use tutomvc\wp\imagesize\model\ImageSizeVO;

	class ImageSizeNamesChooseFilter extends FilterCommand
	{
		public function execute()
		{
			$defaultImageSizes = func_get_arg( 0 );

			/** @var ImageSizeVO $imageSize */
			foreach ( ImageSizeModule::getProxy()->getMap() as $imageSize )
			{
				$defaultImageSizes[ $imageSize->getName() ] = $imageSize->getTitle();
			}

			return $defaultImageSizes;
		}
	}