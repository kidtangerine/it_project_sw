<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Itproject
 * @author     nrad hanxo <>
 * @copyright  2016 modern services
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Itproject.
 *
 * @since  1.6
 */
class ItprojectViewProjects extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		ItprojectHelpersItproject::addSubmenu('projects');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = ItprojectHelpersItproject::getActions();

		JToolBarHelper::title(JText::_('COM_ITPROJECT_TITLE_PROJECTS'), 'projects.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/project';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('project.add', 'JTOOLBAR_NEW');
				JToolbarHelper::custom('projects.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('project.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('projects.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('projects.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'projects.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('projects.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('projects.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'projects.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('projects.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_itproject');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_itproject&view=projects');

		$this->extra_sidebar = '';
		//Filter for the field project_department
		$select_label = JText::sprintf('COM_ITPROJECT_FILTER_SELECT_LABEL', 'Project Department');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "admissions";
		$options[0]->text = "Admissions";
		$options[1] = new stdClass();
		$options[1]->value = "continuing_education";
		$options[1]->text = "Continuing Education";
		$options[2] = new stdClass();
		$options[2]->value = "its";
		$options[2]->text = "Information Technology Services";
		$options[3] = new stdClass();
		$options[3]->value = "library";
		$options[3]->text = "Library";
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_project_department',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.project_department'), true)
		);

		//Filter for the field project_status
		$select_label = JText::sprintf('COM_ITPROJECT_FILTER_SELECT_LABEL', 'Project Status');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "in_progress";
		$options[0]->text = "In Progress";
		$options[1] = new stdClass();
		$options[1]->value = "completed";
		$options[1]->text = "Completed";
		$options[2] = new stdClass();
		$options[2]->value = "Initiating";
		$options[2]->text = "Initiating";
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_project_status',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.project_status'), true)
		);

		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);
			//Filter for the field project_start_date
		$this->extra_sidebar .= '<div class="other-filters">';
			$this->extra_sidebar .= '<small><label for="filter_from_project_start_date">'. JText::sprintf('COM_ITPROJECT_FROM_FILTER', 'Start Date') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.project_start_date.from'), 'filter_from_project_start_date', 'filter_from_project_start_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_project_start_date">'. JText::sprintf('COM_ITPROJECT_TO_FILTER', 'Start Date') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.project_start_date.to'), 'filter_to_project_start_date', 'filter_to_project_start_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
		$this->extra_sidebar .= '</div>';
			$this->extra_sidebar .= '<hr class="hr-condensed">';

			//Filter for the field project_end_date
		$this->extra_sidebar .= '<div class="other-filters">';
			$this->extra_sidebar .= '<small><label for="filter_from_project_end_date">'. JText::sprintf('COM_ITPROJECT_FROM_FILTER', 'Project End Date') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.project_end_date.from'), 'filter_from_project_end_date', 'filter_from_project_end_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_project_end_date">'. JText::sprintf('COM_ITPROJECT_TO_FILTER', 'Project End Date') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.project_end_date.to'), 'filter_to_project_end_date', 'filter_to_project_end_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
		$this->extra_sidebar .= '</div>';
			$this->extra_sidebar .= '<hr class="hr-condensed">';

	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`project_name`' => JText::_('COM_ITPROJECT_PROJECTS_PROJECT_NAME'),
			'a.`project_description`' => JText::_('COM_ITPROJECT_PROJECTS_PROJECT_DESCRIPTION'),
			'a.`project_department`' => JText::_('COM_ITPROJECT_PROJECTS_PROJECT_DEPARTMENT'),
			'a.`project_status`' => JText::_('COM_ITPROJECT_PROJECTS_PROJECT_STATUS'),
			'a.`project_completion_status`' => JText::_('COM_ITPROJECT_PROJECTS_PROJECT_COMPLETION_STATUS'),
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`project_start_date`' => JText::_('COM_ITPROJECT_PROJECTS_PROJECT_START_DATE'),
			'a.`project_end_date`' => JText::_('COM_ITPROJECT_PROJECTS_PROJECT_END_DATE'),
		);
	}
}
