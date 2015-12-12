<?php
namespace tutomvc\wp;

class UserMetaProxy extends Proxy
{
	const NAME = __CLASS__;

	private $_hasRegisteredDependencies = FALSE;

	public function onRegister()
	{
		// $dummy = new UserMetaBox( "dummy_user_meta", "Dummy User Meta", 1 );
		// $dummy->addField( new MetaField(
		// 	"dummy_field",
		// 	"Dummy Field",
		// 	"Dummy description"
		// ) );
		// $dummy->addField( new MetaField(
		// 	"dummy_field2",
		// 	"Dummy Field2",
		// 	"Dummy description2",
		// 	MetaField::TYPE_TEXTAREA_WYSIWYG
		// ) );
		// $dummy->addField( new MetaField(
		// 	"dummy_field3",
		// 	"Dummy Field3",
		// 	"Dummy description3",
		// 	MetaField::TYPE_ATTACHMENT,
		// 	array(
		// 	)
		// ) );
		// $dummy->addField( new MetaField(
		// 	"dummy_field4",
		// 	"Dummy Field4",
		// 	"Dummy description4",
		// 	MetaField::TYPE_LINK
		// ) );
		// $this->add($dummy);
	}

	public function add( $item, $key = NULL )
	{
		parent::add( $item, $item->getName() );

		$this->registerDependencies();
	}

	private function registerDependencies()
	{
		if(!$this->_hasRegisteredDependencies)
		{
			/* ACTIONS */
			$this->getFacade()->controller->registerCommand( new RenderUserMetaBoxCommand() );
			$this->getFacade()->controller->registerCommand( new ProfileUpdateActionCommand() );
			/* FILTERS */
			$this->getFacade()->controller->registerCommand( new GetUserMetaDataFilter() );

			$this->_hasRegisteredDependencies = TRUE;
		}
	}
}