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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Thu Jan 05 21:12:31 CET 2012.
 */

/**
 * The muticketActionUrl modifier creates the URL for a given action.
 *
 * @param string $urlType      The url type (admin, user, etc.)
 * @param string $urlFunc      The url func (view, display, edit, etc.)
 * @param array  $urlArguments The argument array containing ids and other additional parameters
 *
 * @return string Desired url in encoded form.
 */
function smarty_modifier_muticketActionUrl($urlType, $urlFunc, $urlArguments)
{
    return DataUtil::formatForDisplay(ModUtil::url('MUTicket', $urlType, $urlFunc, $urlArguments));
}