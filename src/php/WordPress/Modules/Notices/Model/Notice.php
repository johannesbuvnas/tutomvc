<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/11/15
	 * Time: 18:04
	 */

	namespace TutoMVC\WordPress\Modules\Notices\Model;

	use TutoMVC\Model\Simple\ValueObject;
	use TutoMVC\WordPress\Modules\Notices\NoticesModule;

	class Notice extends ValueObject
	{
		function __construct( $content, $type = NoticesModule::TYPE_UPDATE )
		{
			parent::__construct( $type, $content );
		}
	}
