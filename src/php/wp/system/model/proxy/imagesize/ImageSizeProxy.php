<?php
namespace tutomvc\wp;

final class ImageSizeProxy extends Proxy
{
	const NAME = __CLASS__;

	function onRegister()
	{
		$this->getFacade()->controller->registerCommand( new ImageSizeSelectionFilter() );
	}

	public function add( $item, $key = NULL )
	{
		add_image_size( $item->getName(), $item->getWidth(), $item->getHeight(), $item->getCrop() );

		return parent::add( $item, $item->getName() );
	}

	public function getImageSizeName($width = 1, $height = 1)
	{
		if(is_null($width)) $width = 1;
		if(is_null($height)) $height = 1;

		$ratio = abs( $width / $height );

		$comparison = 0;
		$comparisons = array();

		foreach($this->getMap() as $key => $imageSizeVO)
		{
			$comparison = abs( $imageSizeVO->ratio - $ratio );
			if($imageSizeVO->getWidth()) $comparison = abs( $comparison + abs( $width - $imageSizeVO->getWidth() ) );
			if($imageSizeVO->getHeight()) $comparison = abs( $comparison + abs( $height - $imageSizeVO->getHeight() ) );

			$comparisons[] = array
			(
				"ratio" => $comparison,
				"key" => $key
			);
		}

		usort( $comparisons, array( $this, "sortByRatioArrayDESC" ) );

		return $this->get( $comparisons[0]['key'] );
	}

	public function sortByRatio($a, $b)
	{
		return $a->ratio == $b->ratio ? 0 : ( $a->ratio < $b->ratio ) ? 1 : -1;
	}

	public function sortByRatioArrayDESC($a, $b)
	{
		return $a['ratio'] == $b['ratio'] ? 0 : ( $a['ratio'] > $b['ratio'] ) ? 1 : -1;
	}
}