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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Wed Jan 11 12:10:27 CET 2012.
 */

/**
 * This handler class handles the page events of the Form called by the MUTicket_user_edit() function.
 * It aims on the rating object type.
 */
class MUTicket_Form_Handler_User_Rating_Edit extends MUTicket_Form_Handler_User_Rating_Base_Edit
{

	/**
	 * Get url to redirect to.
	 */
	protected function getRedirectUrl($args, $obj)
	{
		$this->ticket = $this->request->query->filter('parent', 0, FILTER_SANITIZE_NUMBER_INT);
        $this->returnTo = 'userDisplayTicket';
		$this->inlineUsage = false;
		
		return parent::getRedirectUrl($args, $obj);
	}
    /**
     * Post-initialise hook.
     *
     * @return void
     */
    public function postInitialize()
    {
        parent::postInitialize();
        
        $ticket = $this->request->query->filter('ticket', 0, FILTER_SANITIZE_NUMBER_INT);
   		
		// We get the input for the form radiobutton
		$ratingvalue = MUTicket_Util_Controller::getRatingValues();
		 
		$rating = $this->view->get_template_vars('rating');
		$rating = $ratingvalue;
		$this->view->assign('rating', $rating)
		           ->assign('ticketid', $ticket);
		 
	}
}
