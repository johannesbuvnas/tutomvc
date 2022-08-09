<?php

	namespace TutoMVC\WordPress\Modules\ImageSize\Controller\Filter;

	use TutoMVC\WordPress\Core\Controller\Command\FilterCommand;
	use TutoMVC\WordPress\Modules\ImageSize\ImageSizeModule;
	use TutoMVC\WordPress\Modules\ImageSize\Model\ImageSizeVO;

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
