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
 * The muticketSelectorSupporter plugin provides items for a dropdown selector.
 *
 * Available parameters:
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out.
 *
 * @param  array            $params  All attributes passed to this function from the template.
 * @param  Zikula_Form_View $view    Reference to the view object.
 *
 * @return string The output of the plugin.
 */
function smarty_function_muticketSelectorSupporter($params, $view)
{
    $where = "tbl.state = 1";
    $args = array('where' =>$where,
                  'ot' => 'supporter');
    $supporters = ModUtil::apiFunc('MUTicket', 'selection', 'getEntities' , $args);
    $result = array();
    
    foreach ($supporters as $supporter) {
        $result[] = array('text' => $supporter['username'], 'value' => $supporter['id']);
    }

    if (array_key_exists('assign', $params)) {
        $view->assign($params['assign'], $result);
        return;
    }
    return $result;
}
