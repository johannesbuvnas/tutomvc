<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 25/11/14
	 * Time: 15:21
	 */

	namespace tutomvc\modules\git;

	use tutomvc\FilterCommand;
	use tutomvc\Proxy;
	use tutomvc\TutoMVC;

	class GitKeyProxy extends Proxy
	{
		const NAME                         = __CLASS__;
		const FORMAT_KEY_HOST              = 'bin/git/keys/object_id_%1$s';
		const FORMAT_PRIVATE_KEY_FILE_PATH = 'bin/git/keys/object_id_%1$s/.ssh/id_rsa';
		const FORMAT_PUBLIC_KEY_FILE_PATH  = 'bin/git/keys/object_id_%1$s/.ssh/id_rsa.pub';
		const ACTION_ADD                   = "gitmodule/model/proxy/GitKeyProxy/add";
		const ACTION_DELETE                = "gitmodule/model/proxy/GitKeyProxy/delete";
		const FILTER_CAT_PRIVATE_KEY       = "gitmodule/model/proxy/GitKeyProxy/catPrivateKey";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function onRegister()
		{
			add_filter( FilterCommand::constructFilterMetaValueCommandName( GitKeyMetaBox::NAME, GitKeyMetaBox::PRIVATE_KEY ), array(
				$this,
				"onGetPrivateKey"
			), 99, 2 );

			add_filter( FilterCommand::constructFilterMetaValueCommandName( GitKeyMetaBox::NAME, GitKeyMetaBox::PUBLIC_KEY ), array(
				$this,
				"onGetPublicKey"
			), 99, 2 );

			add_filter( FilterCommand::constructFilterMetaValueCommandName( GitKeyMetaBox::NAME, GitKeyMetaBox::FINGERPRINT ), array(
				$this,
				"onGetPublicKeyFingerprint"
			), 99, 2 );

			add_action( self::ACTION_ADD, array($this, "add"), 0, 3 );
			add_action( self::ACTION_DELETE, array($this, "delete"), 0, 1 );
			add_filter( self::FILTER_CAT_PRIVATE_KEY, array($this, "catPrivateKey"), 0, 1 );
		}

		public function add( $objectID, $key = NULL, $override = FALSE )
		{
			$label = sanitize_email( get_the_title( $objectID ) );
			if ( !strlen( $label ) ) $label = sanitize_title_for_query( get_the_title( $objectID ) );
			$keyFilePath = self::locatePrivateSSHKey( $objectID );
			$passphrase  = get_post_meta( $objectID, GitKeyMetaBox::constructMetaKey( GitKeyMetaBox::NAME, GitKeyMetaBox::PASSPHRASE ), TRUE );

			global $systemFacade;
			exec( $systemFacade->getVO()->getRoot( "src/shell/ssh-keygen.sh" ) . " $label $keyFilePath $passphrase", $output, $returnVar );

			return $returnVar == 0 ? TRUE : FALSE;
		}

		public function delete( $objectID )
		{
			exec( "rm -rf " . self::locateSSHKeyHost( $objectID ), $output, $returnVar );

			return $this;
		}

		public function readSSHKeyFingerprint( $objectID )
		{
			exec( "ssh-keygen -lf " . self::locatePublicSSHKey( $objectID ), $output, $returnVar );

			return $returnVar == 0 ? $output : NULL;
		}

		public function catPrivateKey( $objectID )
		{
			exec( "cat " . self::locatePrivateSSHKey( $objectID ), $output, $returnVar );

			return $returnVar == 0 ? $output : NULL;
		}

		public function catPublicKey( $objectID )
		{
			exec( "cat " . self::locatePublicSSHKey( $objectID ), $output, $returnVar );

			return $returnVar == 0 ? $output : NULL;
		}

		public static function locateSSHKeyHost( $objectID )
		{
			return TutoMVC::getRoot( sprintf( self::FORMAT_KEY_HOST, $objectID ) );
		}

		public static function locatePrivateSSHKey( $objectID )
		{
			return TutoMVC::getRoot( sprintf( self::FORMAT_PRIVATE_KEY_FILE_PATH, $objectID ) );
		}

		public static function locatePublicSSHKey( $objectID )
		{
			return TutoMVC::getRoot( sprintf( self::FORMAT_PUBLIC_KEY_FILE_PATH, $objectID ) );
		}

		/* HOOKS */
		function onGetPrivateKey( $value, $objectID )
		{
			$value = $this->catPrivateKey( $objectID );

			return $value;
		}

		function onGetPublicKey( $value, $objectID )
		{
			$value = $this->catPublicKey( $objectID );

			return $value;
		}

		function onGetPublicKeyFingerprint( $value, $objectID )
		{
			return $this->readSSHKeyFingerprint( $objectID );
		}
	}