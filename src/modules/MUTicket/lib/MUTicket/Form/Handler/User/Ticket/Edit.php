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
        parent::initialize($view);
        
        // we rule the text for the button to create tickets or answers
        $func = $this->request->query->filter('func', 'display', FILTER_SANITIZE_STRING);
		
        $fileSize = MUTicket_Util_Controller::maxSize();
        
        // set mode to create
		$this->mode = 'create';
		
		// we assign to template
		$this->view->assign('mode', $this->mode)
		           ->assign('func', $func)
		           ->assign('fileSize', $fileSize);

        // everything okay, no initialization errors occured
        return true;
    }	
    
    
    /**
     * Get the default redirect url. Required if no returnTo parameter has been supplied.
     * This method is called in handleCommand so we know which command has been performed.
     */
    protected function getDefaultReturnUrl($args, $obj)
    {
    	//  build request object
    	$request = new Zikula_Request_Http();
    	// get id of parent ticket
    	$parent = $request->getGet()->filter('parent', NULL, FILTER_SANITIZE_NUMBER_INT);
    	
        // redirect to the list of tickets
        $viewArgs = array('ot' => $this->objectType);
        $url = ModUtil::url($this->name, 'user', 'view', $viewArgs);

        if ($args['commandName'] != 'delete' && !($this->mode == 'create' && $args['commandName'] == 'cancel')) {
            // redirect to the detail page of parent ticket
            $url = ModUtil::url($this->name, 'user', 'display', array('ot' => 'ticket', 'id' => $parent ));
        }
        if ($args['commandName'] == 'create' && $this->mode == 'create' && $parent == NULL) {
        	// redirect to just created parent ticket
        	$url = ModUtil::url($this->name, 'user', 'display', array('ot' => 'ticket', 'id' => $this->idValues['id'] ));
        }
        return $url;
    }
}
