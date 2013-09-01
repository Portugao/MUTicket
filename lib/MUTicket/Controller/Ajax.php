<?php
/**
 * MUTicket.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUTicket
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Sun Aug 18 12:09:50 CEST 2013.
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

    public function close() {
         
        // may the user close the ticket?
        $kind = MUTicket_Util_View::userForRating();
        if ($kind == 1) {
            return false;
        }
        // we get the relevant id
        $request = new Zikula_Request_Http();
        $id = $request->getGet()->filter('ticket', 0, FILTER_SANITIZE_NUMBER_INT);
        // we close the ticket
        MUTicket_Util_Model::closeTicket($id);
         
        return System::redirect(ModUtil::url($this->name, 'user', 'view' , array('ot' => 'ticket', 'state' => 3)));
    }

}