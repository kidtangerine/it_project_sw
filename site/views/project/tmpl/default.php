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


?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_ITPROJECT_FORM_LBL_PROJECT_PROJECT_NAME'); ?></th>
			<td><?php echo $this->item->project_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ITPROJECT_FORM_LBL_PROJECT_PROJECT_DESCRIPTION'); ?></th>
			<td><?php echo nl2br($this->item->project_description); ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ITPROJECT_FORM_LBL_PROJECT_PROJECT_DEPARTMENT'); ?></th>
			<td><?php echo $this->item->project_department; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ITPROJECT_FORM_LBL_PROJECT_PROJECT_STATUS'); ?></th>
			<td><?php echo $this->item->project_status; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ITPROJECT_FORM_LBL_PROJECT_PROJECT_COMPLETION_STATUS'); ?></th>
			<td><?php echo $this->item->project_completion_status; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ITPROJECT_FORM_LBL_PROJECT_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ITPROJECT_FORM_LBL_PROJECT_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ITPROJECT_FORM_LBL_PROJECT_MODIFIED_BY'); ?></th>
			<td><?php echo $this->item->modified_by_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ITPROJECT_FORM_LBL_PROJECT_PROJECT_START_DATE'); ?></th>
			<td><?php echo $this->item->project_start_date; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ITPROJECT_FORM_LBL_PROJECT_PROJECT_END_DATE'); ?></th>
			<td><?php echo $this->item->project_end_date; ?></td>
		</tr>

	</table>

</div>

