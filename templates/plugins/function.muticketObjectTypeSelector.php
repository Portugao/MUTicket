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
 * The muticketObjectTypeSelector plugin provides items for a dropdown selector.
 *
 * Available parameters:
 *   - assign: If set, the results are assigned to the corresponding variable instead of printed out.
 *
 * @param  array            $params All attributes passed to this function from the template.
 * @param  Zikula_Form_View $view   Reference to the view object.
 *
 * @return string The output of the plugin.
 */
function smarty_function_muticketObjectTypeSelector($params, $view)
{
    $dom = ZLanguage::getModuleDomain('MUTicket');
    $result = array();

    $result[] = array('text' => __('Tickets', $dom), 'value' => 'ticket');
    $result[] = array('text' => __('Ratings', $dom), 'value' => 'rating');
    $result[] = array('text' => __('Supporters', $dom), 'value' => 'supporter');
    $result[] = array('text' => __('Current states', $dom), 'value' => 'currentState');
    $result[] = array('text' => __('Labels', $dom), 'value' => 'label');

    if (array_key_exists('assign', $params)) {
        $view->assign($params['assign'], $result);

        return;
    }

    return $result;
}
