<?php
namespace tutomvc;

class GetMetaValueFilterCommand extends FilterCommand
{

	function __construct()
	{
		parent::__construct( FilterCommand::META_VALUE );
		$this->acceptedArguments = 3;	
	}

	function execute()
	{
		$postID = $this->getArg(0);
		$metaValue = $this->getArg(1);
		$metaField = $this->getArg(2);

		$settings = $metaField->getSettings();
		
		switch( $metaField->getType() )
		{
			case MetaField::TYPE_ATTACHMENT:

				$metaValue = $this->constructAttachmentMap( $metaValue );

			break;
			case MetaField::TYPE_TEXTAREA_WYSIWYG:

				$metaValue = $this->constructRichTextAreaMap( $metaValue );

			break;
		}

		if( is_array($metaValue) && count($metaValue) == 1 && is_string( $metaValue[0] ) ) $metaValue = $metaValue[0];

		if((!isset($metaValue) || empty($metaValue)) && (isset($settings[ MetaField::SETTING_DEFAULT_VALUE ]) || isset($settings[ MetaField::SETTING_DEFAULT_VALUE_CALLBACK ])))
		{
			if(isset($settings[ MetaField::SETTING_DEFAULT_VALUE ])) $metaValue = $settings[ MetaField::SETTING_DEFAULT_VALUE ];
			else if(isset($settings[ MetaField::SETTING_DEFAULT_VALUE_CALLBACK ])) $metaValue = call_user_func_array( $settings[ MetaField::SETTING_DEFAULT_VALUE_CALLBACK ], $postID, $metaField );
		}

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
					"fileType" => wp_check_filetype( $title ),
					"editURL" => get_edit_post_link( $attachmentID )
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