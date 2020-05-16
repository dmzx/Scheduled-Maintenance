<?php
/**
 *
 * Scheduled Maintenance. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, dmzx, https://www.dmzx-web.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\scheduledmaintenance\acp;

/**
 * Scheduled Maintenance ACP module info.
 */
class main_info
{
	public function module()
	{
		return [
			'filename'	=> '\dmzx\scheduledmaintenance\acp\main_module',
			'title'		=> 'ACP_SCHEDULEDMAINTENANCE_TITLE',
			'modes'		=> [
				'settings'	=> [
					'title'	=> 'ACP_SCHEDULEDMAINTENANCE',
					'auth'	=> 'ext_dmzx/scheduledmaintenance && acl_a_board',
					'cat'	=> ['ACP_SCHEDULEDMAINTENANCE_TITLE']
				],
			],
		];
	}
}
