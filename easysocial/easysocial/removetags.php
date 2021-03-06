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

require_once JPATH_SITE.'/plugins/api/easysocial/libraries/mappingHelper.php';
require_once JPATH_SITE.'/components/com_easysocial/controllers/videos.php';

class EasysocialApiResourceRemoveTags extends ApiResource
{
	/**
	 * get.
	 *
	 * @see        JController
	 * @since      1.0
	 * @return true or null
	 */
	public function post()
	{
		$app = JFactory::getApplication();        
        
		// Get the tag id
		$id = $app->input->get('friends_tagsid', 0, 'int');

		// Get the tag
		$tag = ES::table('Tag');
		$tag->load($id);

		// Check for permissions to delete this tag
		$table = ES::table('Video');
		$table->load($tag->target_id);

		$video = ES::video($table->uid, $table->type, $table);
		
		// Delete the tag
		$tag->delete();
		$video = ES::video();
		$video->load($tag->target_id);
		$tag_peoples=$video->getTags();

		$this->plugin->setResponse($tag_peoples);
	}
}
