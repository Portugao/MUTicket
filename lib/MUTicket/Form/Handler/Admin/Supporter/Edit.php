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
 * This handler class handles the page events of the Form called by the MUTicket_admin_edit() function.
 * It aims on the supporter object type.
 */
class MUTicket_Form_Handler_Admin_Supporter_Edit extends MUTicket_Form_Handler_Admin_Supporter_Base_Edit
{
    /**
     * Get the default redirect url. Required if no returnTo parameter has been supplied.
     * This method is called in handleCommand so we know which command has been performed.
     */
    protected function getDefaultReturnUrl($args, $obj)
    {
        // redirect to the list of supporters
        $viewArgs = array('ot' => $this->objectType);
        $url = ModUtil::url($this->name, 'admin', 'view', $viewArgs);

        if ($args['commandName'] != 'delete' && !($this->mode == 'create' && $args['commandName'] == 'cancel')) {
            // redirect to the detail page of treated supporter
            $url = ModUtil::url($this->name, 'admin', 'view', array('ot' => 'supporter'));
        }
        return $url;
    }
}