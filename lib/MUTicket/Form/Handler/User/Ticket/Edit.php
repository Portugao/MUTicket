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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Thu Jan 05 16:12:14 CET 2012.
 */

/**
 * This handler class handles the page events of the Form called by the MUTicket_user_edit() function.
 * It aims on the ticket object type.
 */
class MUTicket_Form_Handler_User_Ticket_Edit extends MUTicket_Form_Handler_User_Ticket_Base_Edit
{
	/**
	 * Initialize form handler.
	 *
	 * This method takes care of all necessary initialisation of our data and form states.
	 *
	 * @return boolean False in case of initialization errors, otherwise true.
	 */
	public function initialize(Zikula_Form_View $view)
	{
		
		$supporterTickets = MUTicket_Util_View::userForRating(2);
		// if supporters may not create tickets
		
		if ($supporterTickets == 0) {
			$uid = UserUtil::getVar('uid');
			$supporteruids = MUTicket_Util_Model::getExistingSupporterUids();
			if (in_array($uid, $supporteruids)) {
				$url = ModUtil::url($this->name, 'user', 'view');
				return LogUtil::registerPermissionError($url);
			}
		}
		
		// We check for supportes that are active
		// If there is no supporter active, we break the input process
		$supporteractive = MUTicket_Util_View::checkIfSupporters();
		if ($supporteractive == 0) {
			$url = ModUtil::url($this->name, 'user', 'view', array('ot' => 'ticket', 'state' => 1));
			return LogUtil::registerError(__('Sorry. Our support is not available at the moment!'),0 , $url);
		}
			
		parent::initialize($view);

		// we rule the text for the button to create tickets or answers
		$func = $this->request->query->filter('func', 'display', FILTER_SANITIZE_STRING);
		$id = $this->request->query->filter('id', 0, FILTER_SANITIZE_NUMBER_INT);

		$fileSize = MUTicket_Util_Controller::maxSize();

		// set mode to create
		$this->mode = 'create';

		// we assign to template
		$this->view->assign('mode', $this->mode)
		->assign('func', $func)
		->assign('ticketid', $id)
		->assign('fileSize', $fileSize);

		// everything okay, no initialization errors occured
		return true;
	}

	/**
	 * Command event handler.
	 *
	 * This event handler is called when a command is issued by the user.
	 */
	public function handleCommand(Zikula_Form_View $view, &$args)
	{
		$supporterTickets = MUTicket_Util_View::userForRating(2);
		// if supporters may not create tickets

		if ($supporterTickets == 0) {
			$uid = UserUtil::getVar('uid');
			$supporteruids = MUTicket_Util_Model::getExistingSupporterUids();
			if (in_array($uid, $supporteruids)) {
				$url = ModUtil::url($this->name, 'user', 'view');
				return LogUtil::registerPermissionError($url);
			}
		}

		$result = parent::handleCommand($view, $args);
		if ($result === false) {
			return $result;
		}
		else {
			// we get parentid
			// We check if ticket is a parent ticket
			$parentid = $this->request->getPost()->filter('muticketTicket_ParentItemList' , null, FILTER_SANITIZE_STRING);
				
			// fetch posted data input values as an associative array
			$formData = $this->view->getValues();
			// we want the array with our field values
			//$entity = $formData[$this->objectTypeLower];
			$repository = MUTicket_Util_Model::getTicketRepository();
			$entity = $repository->selectById($this->idValues['id']);
				
			// Get relevant datas for mailing
			$data['id'] = $this->idValues['id'];
			$data['parentid'] = $parentid;
			$data['title'] = $entity['title'];
			$data['text'] = $entity['text'];
			$data['categories'] = $entity['categories'];

			MUTicket_Util_Base_Settings::handleModvarsPostPersist($data);
		}

	}

	/**
	 * Get success or error message for default operations.
	 *
	 * @param Array   $args    arguments from handleCommand method.
	 * @param Boolean $success true if this is a success, false for default error.
	 * @return String desired status or error message.
	 */
	protected function getDefaultMessage($args, $success = false)
	{
		if ($success !== true) {
			return parent::getDefaultMessage($args, $success);
		}

		$parentid = $this->request->getPost()->filter('muticketTicket_ParentItemList' , null, FILTER_SANITIZE_STRING);
			

		$message = '';
		switch ($args['commandName']) {
			case 'create':
				$message = $this->__('Done! Ticket created.');
				if ($parentid > 0) {
					$message = $this->__('Done! Answer created.');
				}
				break;
			case 'update':
				$message = $this->__('Done! Ticket updated.');
				break;
			case 'delete':
				$message = $this->__('Done! Ticket deleted.');
				break;
		}
		return $message;
	}

	/**
	 * Get the default redirect url. Required if no returnTo parameter has been supplied.
	 * This method is called in handleCommand so we know which command has been performed.
	 */
	protected function getDefaultReturnUrl($args, $obj)
	{
		// we get parentid
		// We check if ticket is a parent ticket
		$parentid = $this->request->getPost()->filter('muticketTicket_ParentItemList' , 0, FILTER_SANITIZE_STRING);

		// redirect to the list of tickets
		$viewArgs = array('ot' => $this->objectType);
		$url = ModUtil::url($this->name, 'user', 'view', $viewArgs);

		if ($args['commandName'] != 'delete' && !($this->mode == 'create' && $args['commandName'] == 'cancel')) {
			// redirect to the detail page of parent ticket
			$url = ModUtil::url($this->name, 'user', 'display', array('ot' => 'ticket', 'id' => $parentid));
		}
		if ($args['commandName'] == 'create' && $this->mode == 'create' && $parentid == 0) {
			// redirect to just created parent ticket
			$url = ModUtil::url($this->name, 'user', 'display', array('ot' => 'ticket', 'id' => $this->idValues['id'] ));
		}

		return $url;
	}
}