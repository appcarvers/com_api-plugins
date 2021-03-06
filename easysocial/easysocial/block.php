<?php
/**
 * @package API plugins
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link http://www.techjoomla.com
*/

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');
jimport('joomla.html.html');

class EasysocialApiResourceBlock extends ApiResource
{
	public function get()
	{
		$this->plugin->setResponse("Use method post");
	}	
	public function post()
	{
		$this->plugin->setResponse($this->processUser());
	}
	
	public function processUser()
	{
		$app = JFactory::getApplication();
		$reason = $app->input->get('reason','','STRING');
		$target_id = $app->input->get('target_id',0,'INT');
		$block_this = $app->input->get('block',0,'INT');
		return $res = ($block_this)?$this->block($target_id,$reason):$this->unblock($target_id);
		
	}
	
	//block user function
	public function block($target_id,$reason)
	{
		$res = new stdClass();
		if(!$target_id)
		{
			$res->success = 0;
			$res->message = JText::_('COM_EASYSOCIAL_INVALID_USER_ID_PROVIDED');
		}

		// Load up the block library
		$lib = FD::blocks();
		$result = $lib->block($target_id, $reason);

		if($result->id)
		{
			$res->success = 1;
			$res->message = JText::_('PLG_API_EASYSOCIAL_BLOCK_USER');
		}
		else
		{
			$res->success = 0;
			$res->message = JText::_('PLG_API_EASYSOCIAL_BLOCK_USER_ERROR');
		}
		return $res;
	}	
	
	public function unblock($target_id)
	{
		$res = new stdClass();
		
		if(!$target_id)
		{
			$res->success = 0;
			$res->message = JText::_('COM_EASYSOCIAL_INVALID_USER_ID_PROVIDED');
			return $res;
		}

		// Load up the block library
		$lib = FD::blocks();
		$result = $lib->unblock($target_id);

		if($result)
		{
			$res->success = 1;
			$res->message = JText::_('PLG_API_EASYSOCIAL_UNBLOCK_USER');
		}
		else
		{
			$res->success = $result->code;
			$res->message = $result->message;
		}
		return $res;
	}
}	
