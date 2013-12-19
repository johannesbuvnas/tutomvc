<?php
namespace tutons;

class GatewayConnection
{
	/* PUBLIC VARS */
	public $url;


	function __construct($url)
	{
		$this->connect( $url );
	}

	public function connect($url)
	{
		$this->url = $url;
	}

	public function call($action, $parameters = array())
	{
		$postData = http_build_query(array(
				"action" => $action,
				"parameters" => $parameters
			));

		$options = array(
				'http' => array(
						'method' => 'POST',
						'header' => 'Content-type: application/x-www-form-urlencoded',
						'content' => $postData
					)
			);

		$context = stream_context_create( $options );

		return file_get_contents( $this->url, false, $context );
	}
}