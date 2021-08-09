<?php
/**
 *
 * Scheduled Maintenance. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2021, dmzx, https://www.dmzx-web.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\scheduledmaintenance\migrations;

use phpbb\db\migration\migration;

class scheduledmaintenance_v101 extends migration
{
	public static function depends_on()
	{
		return [
			'\dmzx\scheduledmaintenance\migrations\scheduledmaintenance_install',
		];
	}

	public function update_data()
	{
		return [
			['config.update', ['dmzx_scheduledmaintenance_version', '1.0.1']],
		];
	}
}
