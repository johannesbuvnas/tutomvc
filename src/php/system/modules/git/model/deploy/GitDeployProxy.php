<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 28/11/14
	 * Time: 21:47
	 */

	namespace tutomvc\modules\git;

	use tutomvc\Proxy;
	use tutomvc\TutoMVC;

	class GitDeployProxy extends Proxy
	{

		const NAME                 = __CLASS__;
		const ACTION_ADD           = "gitmodule/model/GitDeployProxy/add";
		const POST_ACTION          = "gitmodule/model/GitDeployProxy/post_action";
		const POST_ACTION_TRANSFER = "gitmodule/model/GitDeployProxy/post_action_transfer";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function onRegister()
		{
			add_action( self::ACTION_ADD, array($this, "add"), 0, 1 );

			if ( isset($_POST[ 'action' ]) )
			{
				switch ( $_POST[ 'action' ] )
				{
					case self::POST_ACTION:

						$this->onPost();

						break;
					case self::POST_ACTION_TRANSFER:

						$this->onPostTransfer();

						break;
				}
			}
		}

		function add( $deploymentID, $key = NULL, $override = FALSE )
		{
			// CHECK
			/////////////////////////////////////////////////////////////////////////////////////////////////////////
			// Is this item deploying? If not: continue. If yes: Return conflict message.
			$currentStatus = get_post_meta( $deploymentID, StatusMetaField::NAME );
			switch ( $currentStatus )
			{
				case StatusMetaField::PROCESSING:

					die(__( "This webhook has already been triggered and is currently processing.", TutoMVC::NAME ));

					return new WP_Error( 'error', __( "This webhook has already been triggered and is currently processing.", TutoMVC::NAME ) );

					break;
			}
			/////////////////////////////////////////////////////////////////////////////////////////////////////////
			// Is revision submitted? If not: fetch latest revision. If yes: Is the revision submitted valid?
			/////////////////////////////////////////////////////////////////////////////////////////////////////////
			// Is comment submitted? If not: fetch latest commit message
			/////////////////////////////////////////////////////////////////////////////////////////////////////////

			// PROCESS
			// Step 0: Change status to DEPLOYING / PROCESSING
			// Step 1: Clone git
			// Step 2: Fetch file list (all files if deploy from scratch, else list changes) (Is repository path submitted?)
			// Step 3: Connect to FTP and deploy file list
			// Step 4: Post to URL that deployment is done

			// END PROCESS
			// Step 1: Notify people
			// Step 2: Delete files
			// Step 3: Change status to DEPLOYED

			$this->deploy( $deploymentID );

			return TRUE;
		}

		protected function deploy( $deploymentID )
		{
			error_reporting( E_ERROR );
			// PROCESS
			// Step #1: Change status to PROCESSING.
			/////////////////////////////////////////////////////////////////////////////////////////////////////////
			if ( get_post_meta( $deploymentID, StatusMetaField::NAME, TRUE ) == StatusMetaField::ERROR ) return FALSE;

			update_post_meta( $deploymentID, StatusMetaField::NAME, StatusMetaField::PROCESSING );
//			update_post_meta( $deploymentID, DeploymentStageMetaField::NAME, DeploymentStageMetaField::CLONING );

			$currentDeploymentStage = get_post_meta( $deploymentID, DeploymentStageMetaField::NAME, TRUE );
			switch ( $currentDeploymentStage )
			{
				case DeploymentStageMetaField::CLONING:

					// Step #3: Fetch changed files, connect to FTP and upload.
					// Keep in mind: All files if 'deploy from scratch is ticked'
					// Keep in mind: Is repository path submitted?
					/////////////////////////////////////////////////////////////////////////////////////////////////////////
					// If no revision is submitted, set it now to the latest
					// If no comment is submitted, set it now to the latest commit message
					$revision       = $this->getRevision( $deploymentID );
					$comment        = $this->getComment( $deploymentID );
					$repositoryPath = apply_filters( GitRepositoryProxy::FILTER_LOCATE_REPOSITORY_CLONE, $this->getRepositoryID( $deploymentID ) );

					if ( empty($revision) )
					{
						exec( "cd $repositoryPath && git log --pretty=format:'%H' -n 1", $revision, $returnVar );
						$revision = implode( "", $revision );
						update_post_meta( $deploymentID, GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::REVISION ), $revision );
					}
					if ( empty($comment) )
					{
						exec( "cd $repositoryPath && git log $revision --pretty=format:'%s' -n 1", $comment, $returnVar );
						$comment = implode( "", $comment );
						update_post_meta( $deploymentID, GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::COMMENT ), $comment );
					}

					// OK ready to upload
					update_post_meta( $deploymentID, DeploymentStageMetaField::NAME, DeploymentStageMetaField::UPLOADING );
					$this->upload( $deploymentID );

					break;
				case DeploymentStageMetaField::UPLOADING:

					// Step #4: Cleanup. Delete files.
					/////////////////////////////////////////////////////////////////////////////////////////////////////////
					$this->cleanUp( $deploymentID );
					/////////////////////////////////////////////////////////////////////////////////////////////////////////

					// Step #5: Notify people.
					/////////////////////////////////////////////////////////////////////////////////////////////////////////
					$this->notify( $deploymentID );
					/////////////////////////////////////////////////////////////////////////////////////////////////////////

					// Step #6: Update webhook revision.
					/////////////////////////////////////////////////////////////////////////////////////////////////////////
					$revision  = $this->getRevision( $deploymentID );
					$webhookID = $this->getWebhookID( $deploymentID );
					update_post_meta( $webhookID, GitWebhookMetaBox::constructMetaKey( GitWebhookMetaBox::NAME, GitWebhookMetaBox::REVISION ), $revision );
					/////////////////////////////////////////////////////////////////////////////////////////////////////////

					// Step #7: Change status to OK / DONE.
					/////////////////////////////////////////////////////////////////////////////////////////////////////////
					update_post_meta( $deploymentID, DeploymentStageMetaField::NAME, DeploymentStageMetaField::DONE );
					update_post_meta( $deploymentID, StatusMetaField::NAME, StatusMetaField::OK );
					/////////////////////////////////////////////////////////////////////////////////////////////////////////

					break;
				default:

					/////////////////////////////////////////////////////////////////////////////////////////////////////////
					// Step #2: Clone.
					/////////////////////////////////////////////////////////////////////////////////////////////////////////
					update_post_meta( $deploymentID, DeploymentStageMetaField::NAME, DeploymentStageMetaField::CLONING );

					$this->gitClone( $deploymentID );

					break;
			}

			return TRUE;
		}

		public function getComment( $deploymentID )
		{
			return get_post_meta( $deploymentID, GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::COMMENT ), TRUE );
		}

		public function getRevision( $deploymentID )
		{
			return get_post_meta( $deploymentID, GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::REVISION ), TRUE );
		}

		public function getWebhookID( $deploymentID )
		{
			return intval( get_post_meta( $deploymentID, GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::GIT_WEBHOOK ), TRUE ) );
		}

		public function getRepositoryID( $deploymentID )
		{
			return intval( get_post_meta( $this->getWebhookID( $deploymentID ), GitWebhookMetaBox::constructMetaKey( GitWebhookMetaBox::NAME, GitRepositoryPostType::NAME ), TRUE ) );
		}

		public function getServerID( $deploymentID )
		{
			return intval( get_post_meta( $this->getWebhookID( $deploymentID ), GitWebhookMetaBox::constructMetaKey( GitWebhookMetaBox::NAME, ServerPostType::NAME ), TRUE ) );
		}

		public function appendTransferListItem( $deploymentID, $itemArray )
		{
			$metaKey      = GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::TRANSFER_LIST );
			$transferList = get_post_meta( $deploymentID, $metaKey, TRUE );
			if ( empty($transferList) ) return update_post_meta( $deploymentID, $metaKey, json_encode( $itemArray ) );
			else return update_post_meta( $deploymentID, $metaKey, $transferList . json_encode( $itemArray ) );
		}

		protected function cleanUp( $deploymentID )
		{
			$repositoryID   = $this->getRepositoryID( $deploymentID );
			$repositoryPath = apply_filters( GitRepositoryProxy::FILTER_LOCATE_REPOSITORY, $repositoryID );
			exec( "rm -rf $repositoryPath", $output, $returnVar );

			return $returnVar == 0 ? TRUE : FALSE;
		}

		protected function notify( $deploymentID )
		{

		}

		protected function gitClone( $deploymentID )
		{
			$repositoryID   = $this->getRepositoryID( $deploymentID );
			$repositoryPath = apply_filters( GitRepositoryProxy::FILTER_LOCATE_REPOSITORY_CLONE, $repositoryID );
			$gitSSHURL      = get_post_meta( $repositoryID, GitRepositoryMetaBox::constructMetaKey( GitRepositoryMetaBox::NAME, GitRepositoryMetaBox::ADDRESS ), TRUE );
			$branch         = get_post_meta( $repositoryID, GitRepositoryMetaBox::constructMetaKey( GitRepositoryMetaBox::NAME, GitRepositoryMetaBox::BRANCH ), TRUE );
			$keyID          = apply_filters( GitRepositoryProxy::FILTER_KEY_ID, $repositoryID );
			$keyPath        = GitKeyProxy::locatePrivateSSHKey( $keyID );
			$keyPassphrase  = get_post_meta( $keyID, GitKeyMetaBox::constructMetaKey( GitKeyMetaBox::NAME, GitKeyMetaBox::PASSPHRASE ) );

			exec( "chmod +x " . $this->getSystem()->getVO()->getRoot( "src/shell/git-clone.sh" ) );

			exec( $this->getSystem()->getVO()->getRoot( "src/shell/git-clone.sh" ) . " $repositoryPath $gitSSHURL $branch $keyPath $keyPassphrase $deploymentID > /dev/null & echo $!", $pid, $returnVar );
			update_post_meta( $deploymentID, PIDMetaField::NAME, $pid );
//			exec( $this->getSystem()->getVO()->getRoot( "src/shell/git-clone.sh" ) . " $repositoryPath $gitSSHURL $branch $keyPath $keyPassphrase $deploymentID", $output, $returnVar );
//			var_dump( $output, $returnVar );
		}

		protected function upload( $deploymentID )
		{
			$webhookID      = $this->getWebhookID( $deploymentID );
			$repositoryID   = $this->getRepositoryID( $deploymentID );
			$repositoryPath = apply_filters( GitRepositoryProxy::FILTER_LOCATE_REPOSITORY_CLONE, $repositoryID );
			$revision       = apply_filters( GitWebhookProxy::FILTER_REVISION, $webhookID );
			$fromPath       = get_post_meta( $webhookID, GitWebhookMetaBox::constructMetaKey( GitWebhookMetaBox::NAME, GitWebhookMetaBox::GIT_REPOSITORY_PATH ), TRUE );
			if ( empty($fromPath) ) $fromPath = "./";
			$deployFromScratch = get_post_meta( $deploymentID, GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::DEPLOY_FROM_SCRATCH ), TRUE );
			$serverID          = $this->getServerID( $deploymentID );
			$serverAddress     = get_post_meta( $serverID, ServerMetaBox::constructMetaKey( ServerMetaBox::NAME, ServerMetaBox::ADDRESS ) );
			$serverPort        = get_post_meta( $serverID, ServerMetaBox::constructMetaKey( ServerMetaBox::NAME, ServerMetaBox::PORT ) );
			$serverUsername    = get_post_meta( $serverID, ServerMetaBox::constructMetaKey( ServerMetaBox::NAME, ServerMetaBox::USERNAME ) );
			$serverPassword    = get_post_meta( $serverID, ServerMetaBox::constructMetaKey( ServerMetaBox::NAME, ServerMetaBox::PASSWORD ) );
			$serverPath        = get_post_meta( $webhookID, GitWebhookMetaBox::constructMetaKey( GitWebhookMetaBox::NAME, GitWebhookMetaBox::SERVER_PATH ), TRUE );
//			$output = array(
//				$repositoryPath,
//				$revision,
//				$fromPath,
//				$deployFromScratch,
//			    $serverAddress,
//			    $serverPort,
//			    $serverUsername,
//			    $serverPassword,
//			    $serverPath
//			);
//			var_dump($output);
//			exit;
			exec( "chmod +x " . $this->getSystem()->getVO()->getRoot( "src/shell/git-deploy.sh" ) );
			exec( $this->getSystem()->getVO()->getRoot( "src/shell/git-deploy.sh" ) . " $repositoryPath $revision $fromPath $deployFromScratch $serverAddress $serverPort $serverUsername $serverPassword $serverPath $deploymentID > /dev/null & echo $!", $pid, $returnVar );
			update_post_meta( $deploymentID, PIDMetaField::NAME, $pid );
//			exec( $this->getSystem()->getVO()->getRoot( "src/shell/git-deploy.sh" ) . " $repositoryPath $revision $fromPath $deployFromScratch $serverAddress $serverPort $serverUsername $serverPassword $serverPath $deploymentID", $output, $returnVar );
//			echo implode( "\n", $output );
		}

		/* POST ACTION HOOKS */
		protected function onPost()
		{
			error_reporting( E_ERROR );

			$deploymentID = $_POST[ 'objectID' ];
			$exitCode     = $_POST[ 'exitCode' ];

			switch ( intval( $exitCode ) )
			{
				case 0:

					// Current process was successful. Continue deployment.
					$this->deploy( $deploymentID );

					break;
				default:

					// Current process was NOT successful. Interrupt deployment and cleanup.
					update_post_meta( $deploymentID, StatusMetaField::NAME, StatusMetaField::ERROR );

					$this->cleanUp( $deploymentID );

					break;
			}

			exit;
		}

		protected function onPostTransfer()
		{
			error_reporting( E_ERROR );

			$deploymentID     = intval( $_POST[ 'objectID' ] );
			$transferFile     = $_POST[ 'transferFile' ];
			$transferAction   = $_POST[ 'transferAction' ];
			$transferFileSize = $_POST[ 'transferFileSize' ];
			$transferExitCode = $_POST[ 'transferExitCode' ];
			$transferQueueID  = $_POST[ 'transferQueueID' ];
			$transferTotals   = $_POST[ 'transferTotals' ];

			$progression      = $transferQueueID / $transferTotals;
			$transferListItem = array(
				"file"     => urldecode( $transferFile ),
				"action"   => $transferAction,
				"fileSize" => $transferFileSize,
				"exitCode" => $transferExitCode
			);

			update_post_meta( $deploymentID, GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::PROGRESSION ), $progression );

			switch ( intval( $transferExitCode ) )
			{
				case 0:

					// Transfer action succeeded
					$this->appendTransferListItem( $deploymentID, $transferListItem );

					break;
				default:

					// Transfer action failed
					if ( $transferAction == "upload" )
					{
						$this->appendTransferListItem( $deploymentID, $transferListItem );
					}

					break;
			}

			exit;
		}
	}