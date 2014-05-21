<?php
namespace tutomvc;

class UserMetaProxy extends Proxy
{
	const NAME = __CLASS__;

	public function onRegister()
	{
		/* ACTIONS */
		$this->getFacade()->controller->registerCommand( new RenderUserMetaBoxCommand() );
		$this->getFacade()->controller->registerCommand( new ProfileUpdateActionCommand() );
		/* FILTERS */
		$this->getFacade()->controller->registerCommand( new GetUserMetaDataFilter() );

		$dummy = new UserMetaBox( "dummy_user_meta", "Dummy User Meta", -1 );
		$dummy->addField( new MetaField(
			"dummy_field",
			"Dummy Field",
			"Dummy description"
		) );
		$dummy->addField( new MetaField(
			"dummy_field2",
			"Dummy Field2",
			"Dummy description2",
			MetaField::TYPE_TEXTAREA_WYSIWYG
		) );
		$dummy->addField( new MetaField(
			"dummy_field3",
			"Dummy Field3",
			"Dummy description3",
			MetaField::TYPE_ATTACHMENT,
			array(
			)
		) );
		$dummy->addField( new MetaField(
			"dummy_field4",
			"Dummy Field4",
			"Dummy description4",
			MetaField::TYPE_LINK,
			array(
			)
		) );
		$this->add($dummy);
	}

	public function add( $item, $key = NULL )
	{
		parent::add( $item, $item->getName() );
	}
}