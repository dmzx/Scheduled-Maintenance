<?php
/**
 *
 * Scheduled Maintenance. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, dmzx, https://www.dmzx-web.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\scheduledmaintenance\controller;

/**
 * Scheduled Maintenance ACP controller.
 */
class acp_controller
{
	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\textformatter\s9e\parser */
	protected $parser;

	/** @var \phpbb\textformatter\s9e\renderer */
	protected $renderer;

	/** @var \phpbb\textformatter\s9e\utils */
	protected $utils;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\config\db_text					$config_text		Config text object
	 * @param \phpbb\config\config					$config			 Config object
	 * @param \phpbb\language\language				$language			Language object
	 * @param \phpbb\log\log						$log				Log object
	 * @param \phpbb\request\request				$request			Request object
	 * @param \phpbb\template\template				$template			Template object
	 * @param \phpbb\user							$user				User object
	 * @param \phpbb\textformatter\s9e\parser		$parser				Textformatter parser object
	 * @param \phpbb\textformatter\s9e\renderer		$renderer			Textformatter renderer object
	 * @param \phpbb\textformatter\s9e\utils		$utils				Textformatter utilities object
	 */
	public function __construct(
		\phpbb\config\db_text $config_text,
		\phpbb\config\config $config,
		\phpbb\language\language $language,
		\phpbb\log\log $log,
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\textformatter\s9e\parser $parser,
		\phpbb\textformatter\s9e\renderer $renderer,
		\phpbb\textformatter\s9e\utils $utils
	)
	{
		$this->config_text		= $config_text;
		$this->config 			= $config;
		$this->language			= $language;
		$this->log				= $log;
		$this->request			= $request;
		$this->template			= $template;
		$this->user				= $user;
		$this->parser			= $parser;
		$this->renderer			= $renderer;
		$this->utils			= $utils;
	}

	/**
	 * Display the options a user can configure for this extension.
	 *
	 * @return void
	 */
	public function display_options()
	{
		// Add our common language file
		$this->language->add_lang('acp_scheduledmaintenance', 'dmzx/scheduledmaintenance');

		// Create a form key for preventing CSRF attacks
		add_form_key('dmzx_scheduledmaintenance_acp');

		// Create an array to collect errors that will be output to the user
		$errors = [];

		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			// Test if the submitted form is valid
			if (!check_form_key('dmzx_scheduledmaintenance_acp'))
			{
				$errors[] = $this->language->lang('FORM_INVALID');
			}

			// If no errors, process the form data
			if (empty($errors))
			{
				// Set the options the user configured
				$scheduledmaintenancetext = $this->request->variable('scheduledmaintenance', '', true);
				$scheduledmaintenancetext = $this->parser->parse($scheduledmaintenancetext);

				$this->config_text->set('dmzx_scheduledmaintenance', $scheduledmaintenancetext);
				$this->config->set('dmzx_scheduledmaintenance_enable', $this->request->variable('dmzx_scheduledmaintenance_enable', 0));

				// Add option settings change action to the admin log
				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACP_SCHEDULEDMAINTENANCE_SETTINGS');

				// Option settings have been updated and logged
				// Confirm this to the user and provide link back to previous page
				trigger_error($this->language->lang('ACP_SCHEDULEDMAINTENANCE_SETTING_SAVED') . adm_back_link($this->u_action));
			}
		}

		$s_errors = !empty($errors);

		$scheduledmaintenancetext = $this->config_text->get('dmzx_scheduledmaintenance');

		// Set output variables for display in the template
		$this->template->assign_vars([
			'S_ERROR'						=> $s_errors,
			'ERROR_MSG'						=> $s_errors ? implode('<br />', $errors) : '',
			'SCHEDULEDMAINTENANCE_ENABLE'	=> $this->config['dmzx_scheduledmaintenance_enable'],
			'SCHEDULEDMAINTENANCE'			=> $this->renderer->render(htmlspecialchars_decode($scheduledmaintenancetext, ENT_COMPAT)),
			'SCHEDULEDMAINTENANCE_EDIT'		=> $this->utils->unparse($scheduledmaintenancetext),
			'S_SCHEDULEDMAINTENANCE_EDIT'	=> $this->request->is_set('edit_scheduledmaintenance'),
			'SCHEDULEDMAINTENANCE_VERSION'	=> $this->config['dmzx_scheduledmaintenance_version'],
			'U_ACTION'						=> $this->u_action,
		]);
	}

	/**
	 * Set custom form action.
	 *
	 * @param string	$u_action	Custom form action
	 * @return void
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
