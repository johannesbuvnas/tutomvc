<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 11/03/15
	 * Time: 12:01
	 */

	namespace tutomvc\wp\posttype;

	use tutomvc\Facade;

	class PostTypeModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.posttype";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		protected function prepModel()
		{
			$this->registerProxy( new PostTypeProxy() );
		}
	}