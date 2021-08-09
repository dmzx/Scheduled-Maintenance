<?php
/**
 *
 * Scheduled Maintenance. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, dmzx, https://www.dmzx-web.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\scheduledmaintenance\migrations;

use phpbb\db\migration\container_aware_migration;

class scheduledmaintenance_install extends container_aware_migration
{
	public static function depends_on()
	{
		return [
			'\phpbb\db\migration\data\v330\v330'
		];
	}

	public function update_data()
	{
		$parser = $this->container->get('text_formatter.parser');

		$scheduledmaintenance = $parser->parse('We will be performing maintenance for an approximate duration of 2 hours starting at [b]20-05-2020 at 15:50[/b]!');

		return [
			['config.add', ['dmzx_scheduledmaintenance_version', '1.0.0']],
			['config.add', ['dmzx_scheduledmaintenance_enable', 0]],
			['config_text.add', ['dmzx_scheduledmaintenance', $scheduledmaintenance]],

			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_SCHEDULEDMAINTENANCE_TITLE'
			]],
			['module.add', [
				'acp',
				'ACP_SCHEDULEDMAINTENANCE_TITLE',
				[
					'module_basename'	=> '\dmzx\scheduledmaintenance\acp\main_module',
					'modes'				=> ['settings'],
				],
			]],
		];
	}
}
