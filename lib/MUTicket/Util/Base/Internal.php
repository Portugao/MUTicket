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
 * @version Generated by ModuleStudio 0.6.1 (http://modulestudio.de) at Sat Aug 31 12:39:20 CEST 2013.
 */

/**
 * Utility implementation class for workflow helper methods.
 */
class MUTicket_Util_Base_Internal extends Zikula_AbstractBase
{
    /*
     * This function handles the changing of different things of an ticket
    * for example sending email
    */
    public function handleChanges($kind, $email, $ticket)
    {
            $messagecontent = self::getContent($kind, $email, $ticket);
            self::sendMessage($messagecontent);
    }

    public function sendMessage($messagecontent)
    {
        if (!ModUtil::apiFunc('Mailer', 'user', 'sendmessage', $messagecontent)) {
            $success = false;
        }
        else {
            $success = true;
        }

    }

    public function getContent($kind, $email, $ticket)
    {
        include_once 'modules/MUTicket/templates/plugins/modifier.muticketGetCurrentStateDatas.php';
        
        // We get the name of the site
        $from = ModUtil::getVar('ZConfig', 'sitename') . ' ';
        // We get the adminmail
        $fromaddress = ModUtil::getVar('ZConfig', 'adminmail');
        
        $serviceManager = ServiceUtil::getManager();
        $handler = new Zikula_Form_View($serviceManager, 'MUTicket');

        $messagecontent = array();
        $messagecontent['from'] = $from;
        $messagecontent['fromaddress'] = $fromaddress;
        /*$messagecontent['toname'] = 'Webmaster';*/
        $messagecontent['toaddress'] = $email;
        if ($kind == 'supporter') {
            $messagecontent['subject'] = $handler->__('Changing of supporter for a ticket');
        }
        if ($kind == 'currentState') {
            $messagecontent['subject'] = $handler->__('Changing of current state for a ticket');
        }
        if ($kind == 'label') {
            $messagecontent['subject'] = $handler->__('Changing of labels for a ticket');
        }
        if ($kind == 'supporter') {
            $messagecontent['body'] = $handler->__('A Ticket was moved to you as supporter.') . '<br />';
            $messagecontent['body'] .= ModUtil::getVar('MUTicket', 'messageNewOwner');
        }
        if ($kind == 'currentState') {
            $messagecontent['body'] = $handler->__('To a ticket was set another current state.') . '<br />';
            $messagecontent['body'] .= ModUtil::getVar('MUTicket', 'messageNewOwner');
        }
        
        $messagecontent['body'] .= '<h2><br />' . $handler->__('Ticket title')  . ': ' . $ticket['title'] . '</h2>';
        $currentState = smarty_modifier_muticketGetCurrentStateDatas($ticket['currentState'], $kind = 'message');
        $messagecontent['body'] .= $handler->__('Status of ticket') . ': ' . $currentState['title'];
       /* if ($kind == 'state') {
            $messagecontent['body'] = $handler->__('There is an answer to your ticket of the support on '). '<h2>' . $from . '</h2>';
        }
        $messagecontent['body'] .= $handler->__('Title of ticket') . '<br />' . $title . '<br /><br />';
        $messagecontent['body'] .= $handler->__('Text') . '<br />' . $text . '<br /><br />';
        $messagecontent['body'] .= $handler->__('Visit this ticket:') . '<br />';
        $messagecontent['body'] .= '<a href="' . $url . '">' . $url . '</a><br />';
        $messagecontent['altbody'] = '';*/
        $messagecontent['html'] = true;

        return $messagecontent;
    }
}
