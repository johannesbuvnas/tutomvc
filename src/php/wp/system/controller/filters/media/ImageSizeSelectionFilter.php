<?php
namespace tutomvc\wp;

class ImageSizeSelectionFilter extends FilterCommand
{
	function __construct()
	{
		parent::__construct( 'image_size_names_choose' );
	}

	function execute()
	{
		$defaultImageSizes = $this->getArg(0);
		
		foreach($this->getFacade()->model->getProxy( ImageSizeProxy::NAME )->getMap() as $imageSize)
		{
			$defaultImageSizes[ $imageSize->getName() ] = $imageSize->getTitle();
		}

		// var_dump($this->getFacade()->model->getProxy( ImageSizeProxy::NAME )->getMap());

		return $defaultImageSizes;
	}
}