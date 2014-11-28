<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 25/11/14
	 * Time: 09:19
	 */

	namespace tutomvc\modules\git;

	use tutomvc\FilterCommand;
	use tutomvc\MetaBox;
	use tutomvc\MetaCondition;
	use tutomvc\MetaField;
	use tutomvc\TextAreaMetaField;
	use tutomvc\TutoMVC;

	class GitKeyMetaBox extends MetaBox
	{
		const NAME                     = "git_key_settings";
		const FINGERPRINT              = "fingerprint";
		const PRIVATE_KEY              = "private_key";
		const PASSPHRASE               = "passphrase";
		const PASSPHRASE_DEFAULT_VALUE = "nosecret";
		const PUBLIC_KEY               = "public_key";

		function __construct()
		{
			parent::__construct( self::NAME, __( "Settings" ), array(GitKeyPostType::NAME), 1, MetaBox::CONTEXT_NORMAL, MetaBox::PRIORITY_CORE );
			$this->setMinCardinality( 1 );

			$this->addField( new MetaField( self::FINGERPRINT, __( "Fingerprint", TutoMVC::NAME ) ) )
			     ->setSetting( MetaField::SETTING_READ_ONLY, TRUE );
			$this->addField( new TextAreaMetaField( self::PRIVATE_KEY, __( "Private key", TutoMVC::NAME ), "", 5, TRUE ) )
			     ->setSetting( MetaField::SETTING_READ_ONLY, TRUE );
			$this->addField( new MetaField( self::PASSPHRASE, __( "Passphrase", TutoMVC::NAME ) ) )
			     ->setSetting( MetaField::SETTING_DEFAULT_VALUE, self::PASSPHRASE_DEFAULT_VALUE )
			     ->setSetting( MetaField::SETTING_READ_ONLY, TRUE );
			$this->addField( new TextAreaMetaField( self::PUBLIC_KEY, __( "Public key", TutoMVC::NAME ), "", 5, TRUE ) )
			     ->setSetting( MetaField::SETTING_READ_ONLY, TRUE );
		}
	}