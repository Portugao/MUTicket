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
 * Event handler implementation class for module installer events.
 */
class MUTicket_Listener_Installer
{
    /**
     * Listener for the `installer.module.installed` event.
     *
     * Called after a module is successfully installed.
     * Receives `$modinfo` as args.
     */
    public static function moduleInstalled(Zikula_Event $event)
    {
    }

    /**
     * Listener for the `installer.module.upgraded` event.
     *
     * Called after a module is successfully upgraded.
     * Receives `$modinfo` as args.
     */
    public static function moduleUpgraded(Zikula_Event $event)
    {
    }

    /**
     * Listener for the `installer.module.uninstalled` event.
     *
     * Called after a module is successfully uninstalled.
     * Receives `$modinfo` as args.
     */
    public static function moduleUninstalled(Zikula_Event $event)
    {
    }

    /**
     * Listener for the `installer.subscriberarea.uninstalled` event.
     *
     * Called after a hook subscriber area is unregistered.
     * Receives args['areaid'] as the areaId.  Use this to remove orphan data associated with this area.
     */
    public static function subscriberAreaUninstalled(Zikula_Event $event)
    {
    }
}
