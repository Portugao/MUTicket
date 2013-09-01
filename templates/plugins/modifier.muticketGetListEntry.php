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
 * The muticketGetListEntry modifier displays the name
 * or names for a given list item.
 *
 * @param string $value      The dropdown value to process.
 * @param string $objectType The treated object type.
 * @param string $fieldName  The list field's name.
 * @param string $delimiter  String used as separator for multiple selections.
 *
 * @return string List item name.
 */
function smarty_modifier_muticketGetListEntry($value, $objectType = '', $fieldName = '', $delimiter = ', ')
{
    if (empty($value) || empty($objectType) || empty($fieldName)) {
        return $value;
    }

    $serviceManager = ServiceUtil::getManager();
    $helper = new MUTicket_Util_ListEntries($serviceManager);

    return $helper->resolve($value, $objectType, $fieldName, $delimiter);
}
