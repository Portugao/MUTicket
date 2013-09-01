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
 * Utility implementation class for list field entries related methods.
 */
class MUTicket_Util_ListEntries extends MUTicket_Util_Base_ListEntries
{
    /**
     * Get 'current state' list entries.
     *
     * @return array Array with desired list entries.
     */
    public function getCurrentStateEntriesForTicket()
    {
        $states = array();
        
        $states[] = array('value' => '0',
                'text'  => $this->__('Not Set'),
                'title' => '',
                'image' => '');
        
        $selectionArgs = array(
                'ot' => 'currentState'
        );
        
        $currentStates = ModUtil::apiFunc($this->name, 'selection', 'getEntities', $selectionArgs);
        foreach ($currentStates as $currentState)
        $states[] = array('value' => $currentState['id'],
                          'text'  => $currentState['title'],
                          'title' => '',
                          'image' => '');
    
        return $states;
    }
}