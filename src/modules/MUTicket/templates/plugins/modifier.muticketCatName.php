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
 * The muticketImageThumb modifier displays a thumbnail image.
 *
 * @param  int       $id      cat id
 * @param  int       $height     Desired height.
 * @param  array     $thumbArgs  Additional arguments.
 *
 * @return string The thumbnail file path.
 */
function smarty_modifier_muticketCatName($id)
{
    /**
     * By overriding this plugin or the util method called below you may add further thumbnail arguments
     * based on custom conditions.
     */
    $cat = CategoryUtil::getCategoryByID($id);
    
    $name = $cat['name'];
    
    return $name;
}
