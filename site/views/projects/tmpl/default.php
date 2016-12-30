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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_itproject') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'projectform.xml');
$canEdit    = $user->authorise('core.edit', 'com_itproject') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'projectform.xml');
$canCheckin = $user->authorise('core.manage', 'com_itproject');
$canChange  = $user->authorise('core.edit.state', 'com_itproject');
$canDelete  = $user->authorise('core.delete', 'com_itproject');
?>
<link href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
 <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script>
<style media="screen" type="text/css">
</style>
<script>
jQuery(document).ready(function(){
    jQuery('#projectList').DataTable();
} );
</script>
 <script src="/media/jui/js/jquery.modal.js" type="text/javascript" charset="utf-8"></script>
  <link rel="stylesheet" href="/media/jui/css/jquery.modal.css" type="text/css" media="screen" />

<hr id="system-readmore" />
<form action="<?php echo JRoute::_('index.php?option=com_itproject&view=projects'); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
	<table id="projectList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				
			<?php endif; ?>

							
				<th>
				<?php echo JText::_('COM_ITPROJECT_PROJECTS_PROJECT_DEPARTMENT');?>
				</th>
				<th>
				<?php echo JText::_('COM_ITPROJECT_PROJECTS_PROJECT_NAME');?>
				</th>
				<th>
				<?php echo JText::_('COM_ITPROJECT_PROJECTS_PROJECT_STATUS');?>
				</th>
				<th>
				<?php echo JText::_('COM_ITPROJECT_PROJECTS_PROJECT_COMPLETION_STATUS');?>
				</th>
				<th>
				<?php echo JText::_('COM_ITPROJECT_PROJECTS_PROJECT_START_DATE');?>
				</th>
				<th>
				<?php echo JText::_('COM_ITPROJECT_PROJECTS_PROJECT_END_DATE');?>
				</th><th>
				<?php echo JText::_('COM_ITPROJECT_PROJECTS_PROJECT_DESCRIPTION');?>
				</th>

		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_itproject'); ?>

			
			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					<?php /* <td class="center">
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_itproject&task=project.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
	<?php if ($item->state == 1): ?>
		<i class="icon-publish"></i>
	<?php else: ?>
		<i class="icon-unpublish"></i>
	<?php endif; ?>
	</a>
</td> */?>
				<?php endif; ?>

				
				<td>

					<?php echo $item->project_department; ?>
				</td>
				<td>
				<?php echo $item->project_name; ?>
				</td>
				
				<td>

					<?php echo $item->project_status; ?>
				</td>
				
				<td><div class="progress progress-success progress-striped active">
				  <div class="bar" style="width:<?php echo $item->project_completion_status; ?>%">
					<?php echo $item->project_completion_status; ?>%
				  </div>
				</div></td>
				<td>

					<?php echo $item->project_start_date; ?>
				</td>
				<td>

					<?php echo $item->project_end_date; ?>
				</td>
				
				<td>
			<a href="#ex1" rel="modal:open"><button class="description_button">Description</button></a> <div id="ex1" style="display:none;">
			<h3>Project Description</h3>
				<p><?php echo $item->project_description; ?></p>
			  </div>
			</div>
			</td>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($canCreate) : ?>
		<a href="<?php echo JRoute::_('index.php?option=com_itproject&task=projectform.edit&id=0', false, 2); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo JText::_('COM_ITPROJECT_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo JText::_('COM_ITPROJECT_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
