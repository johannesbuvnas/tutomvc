<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 13:40
	 */

	namespace tutomvc\wp\metabox;

	class MetaBoxProxy extends \tutomvc\Proxy
	{
		const NAME = __CLASS__;

		function __construct()
		{
			parent::__construct( self::NAME );
		}
	}