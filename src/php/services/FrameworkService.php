<?php
namespace tutomvc;

class FrameworkService
{
	/* PROTECTED VARS */
	protected $lastPostedDate;

	function __construct()
	{
		add_action( "wp_ajax_getWPQuery", array( $this, 'getWPQuery' ) );
		add_action( "wp_ajax_nopriv_getWPQuery", array( $this, 'getWPQuery' ) );

		add_action( "wp_ajax_getWPQueryWhereDateIsOlder", array( $this, 'getWPQueryWhereDateIsOlder' ) );
		add_action( "wp_ajax_nopriv_getWPQueryWhereDateIsOlder", array( $this, 'getWPQueryWhereDateIsOlder' ) );

		add_action( "wp_ajax_getCategories", array( $this, 'getCategories' ) );
		add_action( "wp_ajax_nopriv_getCategories", array( $this, 'getCategories' ) );
	}

	/* AJAX METHODS */
	public function getCategories()
	{
		if ( !wp_verify_nonce( $_REQUEST['nonce'], "ajax-nonce" ) )
		{
			exit( "No naughty business please" );
		}

		$s = urldecode(sanitize_title( $_REQUEST['s'] ));
		$categories = get_categories();
		$filteredCategories = array();

		if(isset($s) && strlen($s))
		{
			foreach($categories as $category)
			{
				if( preg_match( "/".$s."/i", $category->category_nicename ) )
				{
					$filteredCategories[] = $category;
				}
			}

			echo json_encode( $filteredCategories );
		}
		else
		{
			echo json_encode( $categories );
		}

		die();
	}

	public function getWPQuery()
	{
		if ( !wp_verify_nonce( $_REQUEST['nonce'], "ajax-nonce" ) )
		{
			exit("No naughty business please");
		}

		$wpQuery = new \WP_Query( $_REQUEST['args'] );

		foreach ($wpQuery->posts as $post)
		{
			$post->post_content_preview = WordpressUtil::getPostPreviewContent( $post );

			$post->post_content_filtered = WordpressUtil::getPostContent( $post );

			$post->post_content_trimmed = wp_trim_words($post->post_content, 45, " [...]");

			$post->post_date_filtered = WordpressUtil::getPostDate( $post );

			$post->post_category_cloud = WordpressUtil::getPostCategoryCloud( $post );

			$post->permalink = get_permalink( $post->ID );

			$post->images = WordpressUtil::getPostImages( $post );
		}

		echo json_encode( $wpQuery );

		die();
	}

	public function getWPQueryWhereDateIsOlder()
	{
		if ( !wp_verify_nonce( $_REQUEST['nonce'], "ajax-nonce" ) )
		{
			exit("No naughty business please");
		}

		$this->lastPostedDate = $_REQUEST['date'];
		$args = $_REQUEST['args'];

		add_filter( 'posts_where', array( $this, 'filterWhereDateIsOlder' ) );

		$wpQuery = new \WP_Query( $args );

		remove_filter( 'posts_where', array( $this, 'filterWhereDateIsOlder' ) );

		foreach ($wpQuery->posts as $post)
		{
			$post->post_content_preview = WordpressUtil::getPostPreviewContent( $post );

			$post->post_content_filtered = WordpressUtil::getPostContent( $post );

			$post->post_content_trimmed = wp_trim_words($post->post_content, 45, " [...]");

			$post->post_date_filtered = WordpressUtil::getPostDate( $post );

			$post->post_category_cloud = WordpressUtil::getPostCategoryCloud( $post );

			$post->permalink = get_permalink( $post->ID );

			$post->images = WordpressUtil::getPostImages( $post );
		}

		echo json_encode( $wpQuery );

		die();
	}

	/* FILTER METHODS */
	public function filterWhereDateIsOlder($where = '')
	{
    	$where .= " AND post_date < '".$this->lastPostedDate."'";

    	return $where;
	}
}