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
 * Version information implementation class.
 */
class MUTicket_Version extends MUTicket_Base_Version
{
	public function getMetaData()
	{
		$meta = parent::getMetaData();
		// the displayed name of the module
		$meta['displayname']          = $this->__('MUTicket');		 
		// the module description
        $meta['description']  = $this->__('MUTicket - Handling of support tickets for your customers');
        
        return $meta;
        
	}
}
