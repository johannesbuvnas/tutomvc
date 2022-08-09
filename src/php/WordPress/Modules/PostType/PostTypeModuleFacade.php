<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 11/03/15
	 * Time: 12:01
	 */

	namespace TutoMVC\WordPress\Modules\PostType;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\PostType\Model\PostTypeProxy;

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
