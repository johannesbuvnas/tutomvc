<?php
namespace tutomvc;

class WordPressUtil
{
	/** Check if is set and verify nonce ID. */
	public static function verifyNonce( $nonceID, $name )
	{
		if( isset( $_POST[ $nonceID ] ) )
		{
			if( wp_verify_nonce( $_POST[$nonceID], $name ) )
			{
				return true;
			}
		}

		return false;
	}

	/**
	*	Construct a page title.
	*/
	public static function getPageTitle()
	{
		global $page, $paged;

		$title = wp_title( '|', false, 'right' );

		// Add the blog name.
		$title .= get_bloginfo( 'name' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title .= " | $site_description";

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

		return $title;
	}
	
	/**
	*	Get URL of asset within theme.
	*/
	public static function getThemeAssetURL($assetName)
	{
		return get_bloginfo("template_url")."/".$assetName;
	}
	
	/**
	*	Get the excerpt of post.
	*/
	public static function getPostPreviewContent($post, $words = 55, $more = " [...]")
	{
		$content = StringUtil::stripLinks( apply_filters( "the_content", StringUtil::stripBrackets( $post->post_content ) ) );

		$trimmedContent = StringUtil::stripBrackets( wp_trim_words( $post->post_content, $words, "") );

		
		$trimmedContentArray = explode( " ", $trimmedContent );
		
		$lastWordsCount = 10;
		
		$lastWords = implode( " ", array_slice( $trimmedContentArray, count($trimmedContentArray) - $lastWordsCount, $lastWordsCount ) );
		
		if(strlen($lastWords) > 0)
		{
			while(($pos = strpos( $content, $lastWords )) == 0 && $lastWordsCount > 0)
			{
				$lastWordsCount--;
			
				$lastWords = implode( " ", array_slice( $trimmedContentArray, count($trimmedContentArray) - $lastWordsCount, $lastWordsCount ) );
			}
		}
		
		if(isset($pos) && $pos)
		{
			$pos += strlen($lastWords);
			
			$content = StringUtil::stripLinks( apply_filters( "the_content", substr( $content, 0, $pos ).$more ) );
			
			return $content;
		}
		else
		{
			return $trimmedContent.$more;
		}
	}

	/**
	* Get all img tags.
	*/
	public static function getPostImages($post)
	{
		// $html = str_get_html( apply_filters( "the_content", $post->post_content ) );

		$regexp = '/(<img[^>]*src=".*?(?:pre\.gif|next\.gif)"[^>]*>)/i';
		preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		
		return $matches[1];
	}
	
	/**
	*	Get the content of post.
	*/
	public static function getPostContent($post)
	{
		return apply_filters("the_content", $post->post_content);
	}
	
	/**
	*	Get the date of post.
	*/
	public static function getPostDate($post, $dateFormat = null)
	{
		if(!$dateFormat) $dateFormat = get_option( "date_format" );

		return WordpressUtil::formatDate( $post->post_date, $dateFormat );
	}

	public static function formatDate($date, $dateFormat = null)
	{
		if(!$dateFormat) $dateFormat = get_option( "date_format" );

		return apply_filters('get_the_date',  mysql2date( $dateFormat , $date ) );
	}
	
	/**
	*	Get a list / "cloud" of categories.
	*/
	public static function getPostCategoryCloud($post)
	{
		$list = "<ul class='category-cloud'>";
		
		$categories = wp_get_post_categories( $post->ID );
		
		foreach($categories as $categoryID)
		{
			$category = get_category( $categoryID );
			
			$list .= "<li class='category-cloud-item' data-id='".$categoryID."' data-name='".$category->name."'>";
			$list .= "<a href='".get_category_link($categoryID)."'>";
			$list .= "<span class='category-cloud-item-name'>";
			$list .= $category->name;
			$list .= "</span>";
			$list .= "<span class='category-cloud-item-count'>";
			$list .= $category->count;
			$list .= "</span>";
			$list .= "</a>";
			$list .= "</li>";
		}
		
		$list .= "</ul>";
		
		return $list;
	}

	/**
	*	Uploads attachment from URL, returns WP attachment URL or WP Error object.
	*/
	public static function uploadAttachment( $url, $postID, $description = NULL )
	{
		require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
    	require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
    	require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

		$tmp = download_url( $url );

		preg_match('/[^\?]+/', $url, $matches);
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) )
		{
			@unlink( $file_array['tmp_name'] );
			$file_array['tmp_name'] = '';
		}

		// do the validation and storage stuff
		$id = media_handle_sideload( $file_array, $postID, $description );

		// If error storing permanently, unlink
		if ( is_wp_error($id) )
		{
			@unlink($file_array['tmp_name']);
			return $id;
		}

		return $id;
	}

	/**
	*	Uploads attachment from data, returns WP attachment URL or WP Error object.
	*/
	public static function uploadAttachmentFromByteArray($fileName, $byteArray, $postID, $description = NULL)
	{
		require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
    	require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
    	require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

    	$localFileName = wp_tempnam();
		file_put_contents( $localFileName, $byteArray->data );

		$file_array['name'] = $fileName;
		$file_array['tmp_name'] = $localFileName;

		if ( !is_file( $localFileName ) )
		{
			$file_array['tmp_name'] = '';
		}

		$id = media_handle_sideload( $file_array, $postID, $description );

		if ( is_wp_error($id) )
		{
			@unlink($file_array['tmp_name']);
			return $id;
		}

		return $id;
	}

	public static function isCurrentlyEditingPostType($postType)
	{
		if(!isset($postType)) return false;

		global $pagenow;

		if(isset($_GET['post_type']))
		{
			if($pagenow == "post-new.php" && $_GET['post_type'] == $postType) return true;
			if($pagenow == "post.php" && $_GET['post_type'] == $postType) return true;
			if($pagenow == "post-new.php" && $_POST['post_type'] == $postType) return true;
			if($pagenow == "post.php" && $_POST['post_type'] == $postType) return true;
		}

		if(isset($_GET['action']) && $_GET['action'] == "edit" && isset($_GET['post']))
		{
			$post = get_post($_GET['post']);
			if($post->post_type == $postType) return true;
		}

		if(isset($_POST['action']) && $_POST['action'] == "edit" && isset($_POST['post']))
		{
			$post = get_post($_POST['post']);
			if($post->post_type == $postType) return true;
		}

		return false;
	}

	public static function getImage($imageID, $imageSize)
	{
		$meta = wp_get_attachment_metadata( $imageID );

		if(!$meta['sizes'][$imageSize])
		{
			include( ABSPATH . 'wp-admin/includes/image.php' );
			$file = get_attached_file( $imageID );
			
            wp_update_attachment_metadata( $imageID, \wp_generate_attachment_metadata( $imageID, $file ) );
		}

		return wp_get_attachment_image( $imageID, $imageSize );
	}

	public static function getAttachmentByFilename($filename)
	{
		global $wpdb;

		$query = "SELECT ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.guid LIKE '%{$filename}%' AND {$wpdb->posts}.post_type LIKE 'attachment'";
		
		$result = $wpdb->get_results( $query );

		return $result;
	}

}