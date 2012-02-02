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
 * @version Generated by ModuleStudio 0.5.2 (http://modulestudio.de) at Thu Sep 15 21:40:56 CEST 2011.
 */

/**
 * Repository class used to implement own convenience methods for performing certain DQL queries.
 *
 * This is the concrete repository class for ticket entities.
 */
class MUTicket_Entity_Repository_Ticket extends MUTicket_Entity_Repository_Base_Ticket
{
    /**
     * Select with a given where clause and pagination parameters.
     *
     * @param string  $where          The where clause to use when retrieving the collection (optional) (default='').
     * @param string  $orderBy        The order-by clause to use when retrieving the collection (optional) (default='').
     * @param integer $currentPage    Where to start selection
     * @param integer $resultsPerPage Amount of items to select
     * @param boolean $useJoins       Whether to include joining related objects (optional) (default=true).
     *
     * @return Array with retrieved collection and amount of total records affected by this query.
     */
    public function selectWherePaginated($where = '', $orderBy = '', $currentPage = 1, $resultsPerPage = 25, $useJoins = true)
    {
    	
    $state = (int) FormUtil::getPassedValue('state',2 , 'GET', FILTER_VALIDATE_INT);
    
    if(isset($state)) {	
		if (!empty($where) && $state != 2) {
			$where .= ' AND ';
		}
		if($state != 2) {
		$where .= 'tbl.state = \'' . DataUtil::formatForStore($state) . '\'';
    	}
    }
    
    // handle the view for users also supporters
    // users may see their own tickets and answers
    // supporters may see all
		
    // we check if a user is calling
    $type = (string) FormUtil::getPassedValue('type','' , 'GET', FILTER_SANITIZE_STRING);
    // we check if user is logged in
    if (UserUtil::isLoggedIn() === true) {
    $uid = UserUtil::getVar('uid');
    }
    // guests get no access
    else {
    	LogUtil::registerError(__('Sorry. No access!'));
    	System::redirect($redirecturl);
    }
    
    // we check if the user is a supporter
    if (in_array($uid, MUTicket_Util_View::getExistingSupporterUids()) === false) {
    
    	if($type == 'user') {	
			if (!empty($where)) {
				$where .= ' AND ';
			}
			if (!empty($where)) {			
				$where .= 'tbl.createdUserId = \'' . DataUtil::formatForStore($uid) . '\'';
			}
			else {
				$where = 'tbl.createdUserId = \'' . DataUtil::formatForStore($uid) . '\'';
		}
	}
    }
	            
    return parent::selectWherePaginated($where, $orderBy, $currentPage, $resultsPerPage,
	$useJoins = true);
	
    }    
}
