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
 * Event handler implementation class for theme-related events.
 */
class MUTicket_Listener_Theme
{
    /**
     * Listener for the `theme.preinit` event.
     *
     * Occurs on the startup of the `Zikula_View_Theme#__construct()`.
     * The subject is the Zikula_View_Theme instance.
     * Is useful to setup a customized theme configuration or cache_id.
     */
    public static function preInit(Zikula_Event $event)
    {
    }

    /**
     * Listener for the `theme.init` event.
     *
     * Occurs just before `Zikula_View_Theme#__construct()` finishes.
     * The subject is the Zikula_View_Theme instance.
     */
    public static function init(Zikula_Event $event)
    {
    }

    /**
     * Listener for the `theme.load_config` event.
     *
     * Runs just before `Theme#load_config()` completed.
     * Subject is the Theme instance.
     */
    public static function loadConfig(Zikula_Event $event)
    {
    }

    /**
     * Listener for the `theme.prefetch` event.
     *
     * Occurs in `Theme::themefooter()` just after getting the `$maincontent`.
     * The event subject is `$this` (Theme instance) and has $maincontent as the event data
     * which you can modify with `$event->setData()` in the event handler.
     */
    public static function preFetch(Zikula_Event $event)
    {
    }

    /**
     * Listener for the `theme.postfetch` event.
     *
     * Occurs in `Theme::themefooter()` just after rendering the theme.
     * The event subject is `$this` (Theme instance) and the event data is the rendered
     * output which you can modify with `$event->setData()` in the event handler.
     */
    public static function postFetch(Zikula_Event $event)
    {
    }
}
