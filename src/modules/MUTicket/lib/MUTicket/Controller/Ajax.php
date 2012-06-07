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
 * This is the Ajax controller class providing navigation and interaction functionality.
 */
class MUTicket_Controller_Ajax extends MUTicket_Controller_Base_Ajax
{
	/**
	 *
	 * This function returns the edit form for rating
	 */
    public function voteform() {
    	
    	$request = new Zikula_Request_Http();
    	$ticket = $request->getGet()->filter('ticket', 0, FILTER_SANITIZE_NUMBER_INT);
    	$parent = $request->getGet()->filter('parent', NULL, FILTER_SANITIZE_NUMBER_INT);
    	$result = ModUtil::func($this->name, 'user', 'edit', array('ot' => 'rating', 'ticket' => $ticket, 'parent' => $parent));	
    	
    	return $result;
    } 
     
}
