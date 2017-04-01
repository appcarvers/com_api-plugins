<?php
/**
 * @package API plugins
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link http://www.techjoomla.com
*/
defined('_JEXEC') or die( 'Restricted access' );

error_reporting(E_ERROR | E_PARSE);

jimport('joomla.user.user');

//for image upload
require_once( EBLOG_ADMIN_INCLUDES . '/mediamanager/mediamanager.php' );
require_once( EBLOG_ADMIN_INCLUDES . '/image/image.php' );
require_once( EBLOG_ADMIN_INCLUDES . '/blogimage/blogimage.php' );
require_once( EBLOG_ADMIN_INCLUDES . '/mediamanager/adapters/local.php' );
require_once( EBLOG_ADMIN_INCLUDES . '/mediamanager/adapters/post.php' );
require_once( EBLOG_ADMIN_INCLUDES . '/mediamanager/adapters/posts.php' );
require_once( EBLOG_ADMIN_INCLUDES . '/mediamanager/adapters/abstract.php' );
//require_once( EBLOG_ADMIN_INCLUDES . '/mediamanager/adapters/types/image.php' );

class EasyblogApiResourceImage extends ApiResource
{

	public function __construct( &$ubject, $config = array()) {
		parent::__construct( $ubject, $config = array() );
	}

	public function post()
	{
			
			$input = JFactory::getApplication()->input;
			$log_user = $this->plugin->get('user')->id;
			$res = new stdClass;
			// Let's get the path for the current request.
			$file	= JRequest::getVar( 'file' , '' , 'FILES' , 'array' );


			if($file['name'])
			{
				$key = $post->id;
				$place 	= 'post:'.$key;
				$key =	null;
			
			// The user might be from a subfolder?
			$source	= urldecode('/'.$file['name']);

			$allowed		= EasyBlogImage::canUploadFile( $file , $message );

			if( $allowed !== true )
			{
				$res->status= 0;
				$res->message = JText::_( 'PLG_API_EASYBLOG_UPLOAD_DENIED_MESSAGE' );
				return $res;
			}

			$media 			= new EasyBlogMediaManager();			
			$upload_result		= $media->upload( $file ,$place );

			//adjustment
			$upload_result->key = $place.$source;
			$upload_result->group = 'files';
			$upload_result->parentKey = $place.'|/';
			$upload_result->friendlyPath = 'My Media/'.$source;
			unset($upload_result->variations);

			$this->plugin->setResponse($upload_result);

			return $upload_result;
			
			}
			else
			{
				$this->plugin->setResponse( $this->getErrorResponse(404, __FUNCTION__ . JText::_( 'PLG_API_EASYBLOG_UPLOAD_UNSUCCESSFULL' ) ) );
			}
	}
	
	public function get() {
		$this->plugin->setResponse( $this->getErrorResponse(404, __FUNCTION__ . JText::_( 'PLG_API_EASYBLOG_NOT_SUPPORTED' ) ) );
	}
	
}
