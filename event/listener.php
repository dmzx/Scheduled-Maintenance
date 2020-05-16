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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\textformatter\s9e\renderer */
	protected $renderer;

	/** @var \phpbb\template\template */
	protected $template;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\language\language				$language			Language object
	 * @param \phpbb\config\db_text					$config_text		Config text object
	 * @param \phpbb\config\config					$config			 Config object
	 * @param \phpbb\textformatter\s9e\renderer		$renderer			Textformatter renderer object
	 * @param \phpbb\template\template				$template			Template object
	 */
	public function __construct(
		\phpbb\language\language $language,
		\phpbb\config\db_text $config_text,
		\phpbb\config\config $config,
		\phpbb\textformatter\s9e\renderer $renderer,
		\phpbb\template\template $template
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
