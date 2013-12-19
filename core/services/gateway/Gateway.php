<?php
namespace tutomvc;

class Gateway
{
	/* PROTECTED VARS */
	protected $_allowedIPs;


	function __construct()
	{
		$this->_allowedIPs = array();
	}


	public function open()
	{
		$search = array_search( $_SERVER['REMOTE_ADDR'], $this->_allowedIPs );

		if(is_int($search))
		{
			$params = array( $_POST['action'] );

			foreach($_POST['parameters'] as $param)
			{
				array_push( $params, $param );
			}

			call_user_func_array( "do_action", $params );
		}
		else
		{
			die("Connection not allowed");
		}
	}

	public function allow($ip)
	{
		$search = array_search($_SERVER['REMOTE_ADDR'], $this->_allowedIPs);

		if(!is_int($search)) array_push( $this->_allowedIPs, $ip );
	}
}