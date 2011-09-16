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
 * @version Generated by ModuleStudio 0.5.2 (http://modulestudio.de) at Sun Sep 11 16:08:57 CEST 2011.
 */

/**
 * This is the User api helper class.
 */
class MUTicket_Api_User extends MUTicket_Api_Base_User
{
    /**
     * get available User panel links
     *
     * @return array Array of admin links
     */
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url('MUTicket', 'user', 'view', array('ot' => 'ticket','state' => 1)),
                             'text' => $this->__('Open Tickets'),
                             'title' => $this->__('List of open tickets'));
        }
            if (SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url('MUTicket', 'user', 'view', array('ot' => 'ticket')),
                             'text' => $this->__('Closed Tickets'),
                             'title' => $this->__('List of closed tickets'));
        }
                if (SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_EDIT)) {
            $links[] = array('url' => ModUtil::url('MUTicket', 'user', 'edit', array('ot' => 'ticket')),
                             'text' => $this->__('Create Ticket'),
                             'title' => $this->__('Create Ticket'));
        }
       /* if (SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url('MUTicket', 'user', 'view', array('ot' => 'rating')),
                             'text' => $this->__('Ratings'),
                             'title' => $this->__('Rating list'));
        }
        if (SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url('MUTicket', 'user', 'view', array('ot' => 'supporter')),
                             'text' => $this->__('Supporters'),
                             'title' => $this->__('Supporter list'));
        }*/
        return $links;
    }
}
