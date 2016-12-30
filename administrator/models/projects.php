<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Itproject
 * @author     nrad hanxo <>
 * @copyright  2016 modern services
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Itproject records.
 *
 * @since  1.6
 */
class ItprojectModelProjects extends JModelList
{
/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.`id`',
				'project_name', 'a.`project_name`',
				'project_description', 'a.`project_description`',
				'project_department', 'a.`project_department`',
				'project_status', 'a.`project_status`',
				'project_completion_status', 'a.`project_completion_status`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'project_start_date', 'a.`project_start_date`',
				'project_end_date', 'a.`project_end_date`',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		// Filtering project_department
		$this->setState('filter.project_department', $app->getUserStateFromRequest($this->context.'.filter.project_department', 'filter_project_department', '', 'string'));

		// Filtering project_status
		$this->setState('filter.project_status', $app->getUserStateFromRequest($this->context.'.filter.project_status', 'filter_project_status', '', 'string'));

		// Filtering project_start_date
		$this->setState('filter.project_start_date.from', $app->getUserStateFromRequest($this->context.'.filter.project_start_date.from', 'filter_from_project_start_date', '', 'string'));
		$this->setState('filter.project_start_date.to', $app->getUserStateFromRequest($this->context.'.filter.project_start_date.to', 'filter_to_project_start_date', '', 'string'));

		// Filtering project_end_date
		$this->setState('filter.project_end_date.from', $app->getUserStateFromRequest($this->context.'.filter.project_end_date.from', 'filter_from_project_end_date', '', 'string'));
		$this->setState('filter.project_end_date.to', $app->getUserStateFromRequest($this->context.'.filter.project_end_date.to', 'filter_to_project_end_date', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_itproject');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.project_department', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__itproject_projects` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.project_department LIKE ' . $search . ' )');
			}
		}


		//Filtering project_department
		$filter_project_department = $this->state->get("filter.project_department");
		if ($filter_project_department)
		{
			$query->where("a.`project_department` = '".$db->escape($filter_project_department)."'");
		}

		//Filtering project_status
		$filter_project_status = $this->state->get("filter.project_status");
		if ($filter_project_status)
		{
			$query->where("a.`project_status` = '".$db->escape($filter_project_status)."'");
		}

		//Filtering project_start_date
		$filter_project_start_date_from = $this->state->get("filter.project_start_date.from");
		if ($filter_project_start_date_from) {
			$query->where("a.`project_start_date` >= '".$db->escape($filter_project_start_date_from)."'");
		}
		$filter_project_start_date_to = $this->state->get("filter.project_start_date.to");
		if ($filter_project_start_date_to) {
			$query->where("a.`project_start_date` <= '".$db->escape($filter_project_start_date_to)."'");
		}

		//Filtering project_end_date
		$filter_project_end_date_from = $this->state->get("filter.project_end_date.from");
		if ($filter_project_end_date_from) {
			$query->where("a.`project_end_date` >= '".$db->escape($filter_project_end_date_from)."'");
		}
		$filter_project_end_date_to = $this->state->get("filter.project_end_date.to");
		if ($filter_project_end_date_to) {
			$query->where("a.`project_end_date` <= '".$db->escape($filter_project_end_date_to)."'");
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $oneItem) {
					$oneItem->project_department = JText::_('COM_ITPROJECT_PROJECTS_PROJECT_DEPARTMENT_OPTION_' . strtoupper($oneItem->project_department));
					$oneItem->project_status = JText::_('COM_ITPROJECT_PROJECTS_PROJECT_STATUS_OPTION_' . strtoupper($oneItem->project_status));
		}
		return $items;
	}
}
