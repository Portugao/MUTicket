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
 * Generic single item display content plugin implementation class.
 */
class MUTicket_ContentType_Item extends MUTicket_ContentType_Base_Item
{
    // feel free to extend the content type here
}

function MUTicket_Api_ContentTypes_item($args)
{
    return new MUTicket_Api_ContentTypes_itemPlugin();
}
