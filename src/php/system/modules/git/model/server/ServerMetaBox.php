<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 26/11/14
	 * Time: 09:31
	 */

	namespace tutomvc\modules\git;

	use tutomvc\MetaBox;
	use tutomvc\MetaField;
	use tutomvc\TutoMVC;

	class ServerMetaBox extends MetaBox
	{

		const NAME     = "server_settings";
		const ADDRESS  = "address";
		const PORT     = "port";
		const USERNAME = "username";
		const PASSWORD = "password";

		function __construct()
		{
			parent::__construct( self::NAME, __( "Server", TutoMVC::NAME ), array(ServerPostType::NAME) );
			$this->setMinCardinality( 1 );

			$this->addField( new MetaField( self::ADDRESS, __( "FTP Address", TutoMVC::NAME ), "" ) );
			$this->addField( new MetaField( self::PORT, __( "Port", TutoMVC::NAME ), "" ) )
			     ->setSetting( MetaField::SETTING_DEFAULT_VALUE, "21" );
			$this->addField( new MetaField( self::USERNAME, __( "Username", TutoMVC::NAME ), "" ) );
			$this->addField( new MetaField( self::PASSWORD, __( "Password", TutoMVC::NAME ), "" ) )
			     ->setSetting( MetaField::SETTING_IS_PASSWORD, TRUE );
		}

	}