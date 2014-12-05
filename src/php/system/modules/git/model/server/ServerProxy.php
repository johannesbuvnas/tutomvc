<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 26/11/14
	 * Time: 09:43
	 */

	namespace tutomvc\modules\git;

	use tutomvc\Proxy;

	class ServerProxy extends Proxy
	{
		const NAME        = __CLASS__;
		const FILTER_TEST = "model/proxy/ServerProxy/test";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function onRegister()
		{
			add_filter( self::FILTER_TEST, array($this, "test"), 0, 4 );
		}

		public function test( $address, $port, $username, $password )
		{
			exec( "chmod +x " . $this->getSystem()->getVO()->getRoot( "src/shell/ftp-test.sh" ) );
			exec( $this->getSystem()->getVO()->getRoot( "src/shell/ftp-test.sh" ) . " $address $port $username $password", $output, $returnVar );

//			var_dump( $output, $returnVar );

			return $returnVar == 0 ? TRUE : FALSE;
		}
	}