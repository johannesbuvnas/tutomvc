<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 17/11/14
	 * Time: 09:48
	 */

	namespace tutomvc\modules\git;

	use tutomvc\ActionCommand;
	use tutomvc\TutoMVC;

	class InitAction extends ActionCommand
	{
		const NAME = "init";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function execute()
		{
			$this->prepModel();
			$this->prepView();
			$this->prepController();
		}

		private function prepModel()
		{
			// SSH Key
			$this->getSystem()->postTypeCenter->add( new GitKeyPostType() );
			$this->getSystem()->metaCenter->add( new GitKeyMetaBox() );
			$this->getFacade()->model->registerProxy( new GitKeyProxy() );

			// Server
			$this->getSystem()->postTypeCenter->add( new ServerPostType() );
			$this->getSystem()->metaCenter->add( new ServerMetaBox() );
			$this->getFacade()->model->registerProxy( new ServerProxy() );

			// Repository
			$this->getSystem()->postTypeCenter->add( new GitRepositoryPostType() );
			$this->getSystem()->metaCenter->add( new GitRepositoryMetaBox() );
			$this->getFacade()->model->registerProxy( new GitRepositoryProxy() );

			// Webhook
			$this->getSystem()->postTypeCenter->add( new GitWebhookPostType() );
			$this->getSystem()->metaCenter->add( new GitWebhookMetaBox() );
			$this->getFacade()->model->registerProxy( new GitWebhookProxy() );

			// Deploy
			$this->getSystem()->postTypeCenter->add( new GitDeployPostType() );
			$this->getSystem()->metaCenter->add( new GitDeployMetaBox() );
			$this->getFacade()->model->registerProxy( new GitDeployProxy() );
		}

		private function prepView()
		{
		}

		private function prepController()
		{
		}
	}