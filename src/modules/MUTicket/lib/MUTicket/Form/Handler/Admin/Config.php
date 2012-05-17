
<?php
/**
 * MUTicket.
 *
 * @copyright Michael Ueberschaer
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUTicket
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Thu Feb 02 13:43:18 CET 2012.
 */


/**
 * Configuration handler implementation class
 */
class MUTicket_Form_Handler_Admin_Config extends MUTicket_Form_Handler_Admin_Base_Config
{
	protected function initializeAdditions()
	{
		// make dropdownlist for supporter groups

		ModUtil::dbInfoLoad('Groups');
		$tables = DBUtil::getTables();

		//get groups for the config menue

		$groups = DBUtil::selectObjectArray('groups');
		if ($groups == false) {
			LogUtil::registerError($this->__('Error! No groups available!'), 404);
		}

		$supportgroups = array();

		foreach ($groups as $group) {
			$supportgroups[] = array('value' => $group['name'], 'text' => $group['name']);
		}

		$config = $this->view->get_template_vars('config');
		$config['supportergroupItems'] = $supportgroups;
		$this->view->assign('config', $config);

	}

	/**
	 * Command event handler.
	 *
	 * This event handler is called when a command is issued by the user. Commands are typically something
	 * that originates from a {@link Zikula_Form_Plugin_Button} plugin. The passed args contains different properties
	 * depending on the command source, but you should at least find a <var>$args['commandName']</var>
	 * value indicating the name of the command. The command name is normally specified by the plugin
	 * that initiated the command.
	 * @see Zikula_Form_Plugin_Button
	 * @see Zikula_Form_Plugin_ImageButton
	 */
	public function handleCommand(Zikula_Form_View $view, &$args)
	{
		if ($args['commandName'] == 'save') {
			// check if all fields are valid
			if (!$this->view->isValid()) {
				return false;
			}

			$supportergroup = ModUtil::getVar('MUTicket', 'supportergroup');

			// retrieve form data
			$data = $this->view->getValues();

			// entered supportergroup, is it another?
			$newsupportergroup = $data['config']['supportergroup'];

			// check if existing supporters deleting
			$delete_supporter = $data['supporter']['delete_supporter'];

			// update all module vars
			if (!ModUtil::setVars('MUTicket', $data['config'])) {
				return $this->view->setErrorMsg($this->__('Error! Failed to set configuration variables.'));
			}

			LogUtil::registerStatus($this->__('Done! Module configuration updated.'));
			 
			if($delete_supporter == 1 && $supportergroup != $newsupportergroup) {

				$repository = $this->entityManager->getRepository('MUTicket_Entity_Supporter');
				$objects = $repository->selectWhere();
				foreach($objects as $object) {
					$this->entityManager->remove($object);
					$this->entityManager->flush();
				}

				LogUtil::registerStatus($this->__('All supporters deleted. You have to save one!'));
			}

		} else if ($args['commandName'] == 'cancel') {
			// nothing to do there
		}

		// redirect back to the config page
		$url = ModUtil::url('MUTicket', 'admin', 'config');
		return $this->view->redirect($url);
	}
}
