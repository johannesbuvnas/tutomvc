<?php
	namespace tutomvc;

	class AjaxCommand extends ActionCommand
	{
		/* VARS */
		private $_nonceName;

		function __construct( $nonceName = TutoMVC::NAME )
		{
			parent::__construct();
			$this->setNonceName( $nonceName );
		}

		/* ACTIONS */
		public function register()
		{
			add_action( "wp_ajax_" . $this->getName(), array($this, "onBeforeExecution") );
			add_action( "wp_ajax_nopriv_" . $this->getName(), array($this, "onBeforeExecution") );
		}

		/* SET AND GET */
		public function setNonceName( $nonceName )
		{
			$this->_nonceName = $nonceName;
		}

		public function getNonceName()
		{
			return $this->_nonceName;
		}

		/* EVENTS */
		public function onBeforeExecution()
		{
			if ( !wp_verify_nonce( $_REQUEST[ 'nonce' ], $this->getNonceName() ) )
			{
				exit("No naughty business please.");
			}

			parent::onBeforeExecution();
		}
	}