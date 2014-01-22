<?php
namespace tutomvc;

class GetMetaValueFilterCommand extends FilterCommand
{

	function __construct()
	{
		parent::__construct( FilterCommand::META_VALUE );
		$this->acceptedArguments = 2;	
	}

	function execute( $metaValue, $metaField )
	{
		$settings = $metaField->getSettings();
		
		switch( $metaField->getType() )
		{
			case MetaType::ATTACHMENT:

				$metaValue = $this->constructAttachmentMap( $metaValue );

			break;
			case MetaType::TEXTAREA_WYSIWYG:

				$metaValue = $this->constructRichTextAreaMap( $metaValue );

			break;
		}

		if( is_array($metaValue) && count($metaValue) == 1 && is_string( $metaValue[0] ) ) $metaValue = $metaValue[0];

		if((!isset($metaValue) || empty($metaValue)) && isset($settings['defaultValue'])) $metaValue = $settings['defaultValue'];

		return $metaValue;
	}

	private function constructRichTextAreaMap( $metaValue )
	{
		if(!is_array($metaValue) || count($metaValue) == 0) return $metaValue;

		$newMap = array();
		foreach( $metaValue as $key => $value )
		{
			$newMap[$key] = apply_filters( "the_content", $value );
		}

		return $newMap;
	}

	private function constructAttachmentMap( $attachmentIDMap )
	{
		$map = array();

		if(is_array($attachmentIDMap))
		{
			foreach( $attachmentIDMap as $attachmentID )
			{
				$thumb = wp_get_attachment_image_src( $attachmentID, "thumbnail", false );
				$icon = wp_get_attachment_image_src( $attachmentID, "thumbnail", true );

				$title = basename ( get_attached_file( $attachmentID ) );

				$item = array
				(
					"id" => $attachmentID,
					"title" => $title,
					"thumbnailURL" => $thumb ? $thumb[0] : NULL,
					"iconURL" => $icon ? $icon[0] : NULL,
					"permalink" => get_attachment_link( $attachmentID ),
					"url" => wp_get_attachment_url( $attachmentID ),
					"fileType" => wp_check_filetype( $title )
				);

				if(wp_attachment_is_image( $attachmentID ))
				{
					// Include custom image sizes
					if(count( $this->getFacade()->imageSizeCenter->getMap() ))
					{
						$item['imageSize'] = array();

						foreach($this->getFacade()->imageSizeCenter->getMap() as $imageSize)
						{
							$src = wp_get_attachment_image_src( $attachmentID, $imageSize->getName() );
							$item['imageSize'][ $imageSize->getName() ]['url'] = $src[0];
							$item['imageSize'][ $imageSize->getName() ]['width'] = $src[1];
							$item['imageSize'][ $imageSize->getName() ]['height'] = $src[2];
							$item['imageSize'][ $imageSize->getName() ]['isResized'] = $src[3];
						}
					}
				}

				$map[] = $item;
			}
		}

		return $map;
	}

}