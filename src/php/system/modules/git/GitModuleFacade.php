<?php
/**
 * Created by PhpStorm.
 * User: johannesbuvnas
 * Date: 17/11/14
 * Time: 09:51
 */

namespace tutomvc\modules\git;


use tutomvc\Facade;

/**
 * Class GitModuleFacade
 * TODO: Create GitRepositoryPostType and GitKeyPostType. Create git-test.sh.
 * @package tutomvc\modules\git
 */
class GitModuleFacade extends Facade
{
	function onRegister()
	{
		$this->controller->registerCommand( new InitAction() );
	}
} 