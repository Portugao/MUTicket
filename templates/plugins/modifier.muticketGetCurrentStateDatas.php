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
 * The muticketGetCurrentStateDatas modifier return the datas of an currentState.
 *
 * @param  int       $id      currentState id
 *
 * @return string the category name
 */
function smarty_modifier_muticketGetCurrentStateDatas($id)
{
    
    $selectionArgs = array('id' => $id,
                           'ot' => currentState);
    
	$currentState = ModUtil::apiFunc('MUTicket', 'selection', 'getEntity', $selectionArgs);
	
	$result = "<a href='#' class='tooltip' title='" . $currentState['description'] . "' style='padding: 2px 5px 2px 30px; width: 25px; height: 25px; background-size: cover; background: url(/" . $currentState['uploadIconFullPath'] . ") no-repeat' id='muticket_currentstate_" . $id . "'>" . $currentState['title'] . "</a>";

	return $result;
}
