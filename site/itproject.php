<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Itproject
 * @author     nrad hanxo <>
 * @copyright  2016 modern services
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Itproject', JPATH_COMPONENT);
JLoader::register('ItprojectController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Itproject');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
