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
 * Event handler base class for error-related events.
 */
class MUTicket_Listener_Base_Errors
{
    /**
     * Listener for the `setup.errorreporting` event.
     *
     * Invoked during `System::init()`.
     * Used to activate `set_error_handler()`.
     * Event must `stop()`.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function setupErrorReporting(Zikula_Event $event)
    {
    }
    
    /**
     * Listener for the `systemerror` event.
     *
     * Invoked on any system error.
     * args gets `array('errorno' => $errno, 'errstr' => $errstr, 'errfile' => $errfile, 'errline' => $errline, 'errcontext' => $errcontext)`.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function systemError(Zikula_Event $event)
    {
    }
}