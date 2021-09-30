<?php
/**
 *
 * Scheduled Maintenance. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, dmzx, https://www.dmzx-web.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\scheduledmaintenance\event;

use phpbb\config\config;
use phpbb\config\db_text;
use phpbb\language\language;
use phpbb\template\template;
use phpbb\textformatter\s9e\renderer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var language */
	protected $language;

	/** @var db_text */
	protected $config_text;

	/** @var config */
	protected $config;

	/** @var renderer */
	protected $renderer;

	/** @var template */
	protected $template;

	/**
	 * Constructor.
	 *
	 * @param language				$language			Language object
	 * @param db_text				$config_text		Config text object
	 * @param config				$config			 Config object
	 * @param renderer				$renderer			Textformatter renderer object
	 * @param template				$template			Template object
	 */
	public function __construct(
		language $language,
		db_text $config_text,
		config $config,
		renderer $renderer,
		template $template
	)
	{
		$this->language			= $language;
		$this->config_text		= $config_text;
		$this->config 			= $config;
		$this->renderer			= $renderer;
		$this->template			= $template;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'core.page_footer'	=> 'page_footer',
		];
	}

	public function page_footer():void
	{
		if ($this->config['dmzx_scheduledmaintenance_enable'])
		{
			// Add our common language file
			$this->language->add_lang('common', 'dmzx/scheduledmaintenance');

			$scheduledmaintenancetext = $this->config_text->get('dmzx_scheduledmaintenance');
			$scheduledmaintenance = $this->renderer->render(htmlspecialchars_decode($scheduledmaintenancetext, ENT_COMPAT));

			$this->template->assign_vars([
				'S_SCHEDULED_MAINTENANCE'	=> $this->config['dmzx_scheduledmaintenance_enable'],
				'SCHEDULED_MAINTENANCE'		=> ($scheduledmaintenance !== '') ? $scheduledmaintenance : '',
			]);
		}
	}
}
