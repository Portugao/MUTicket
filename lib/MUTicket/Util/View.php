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
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Sun Aug 18 17:24:13 CEST 2013.
 */

/**
 * Utility implementation class for view helper methods.
 */
class MUTicket_Util_View extends MUTicket_Util_Base_View
{
    /**
     *
     * This method is for getting an array of userids of users that are
     * in the supporter group
     *
     * @return array
     */
    public static function getSupporterIds() {

        ModUtil::dbInfoLoad('Groups');
        $tables = DBUtil::getTables();
        $groups_column = $tables['groups_column'];

        // get supporter group
        $supportergroup = ModUtil::getVar('MUTicket', 'supportergroup');
         
        if ($supportergroup == '') {
            $url = ModUtil::url('MUTicket', 'admin', 'config');
            LogUtil::registerError('You have to save a supporter group!',null , $url);
        }

        $where = "WHERE $groups_column[name] = '" . DataUtil::formatForStore($supportergroup) . "'";

        // get supporter group id
        $supportergroupid = UserUtil::getGroupIdList($where);

        // get user id's of users, which are in the supporter group
        $supporterusersids = UserUtil::getUsersForGroup($supportergroupid);

        $userids = implode(',' , $supporterusersids);

        return $userids;

    }

    /**
     *
     *@return array $catsupporter
     */

    public static function getExistingSupporterForCategories($categoryid) {

        // Get uids of existing supporters
        //$supporteruids = MUTicket_Util_Model::getExistingSupporterUids();
        $repository = MUTicket_Util_Model::getSupporterRepository();
        $supporter = $repository->selectWhere();

        foreach ($supporteruids as $supporteruid) {

            if(in_array($supporteruid, $categoryid)) {
                $catsupporter[] = $supporteruid;
            }
        }

        return $catsupporter;
    }


    /**
     *
     * this method is for checking if an user may create a ticket or if a user may rate
     * @param $ckeck      for what we wish to check 1 = for rating, 2 for creating tickets
     *
     * return int.
     */
    public static function userForRating($check = 1) 
    {
        // get actual userid
        $userid = UserUtil::getVar('uid');
        
        // get the supporterids
        $supporterids = MUTicket_Util_Model::getExistingSupporterUids();
         
        if ($check == 2) {
            $supporterTickets = ModUtil::getVar('MUTicket', 'supporterTickets');
            if ($supporterTickets === true) {
                $kind = 1;

            } else {
                if (in_array($userid, $supporterids)) {
                    $kind = 0;
                } else {
                    $kind = 1;
                }
            }
            return $kind;
        }

        if (in_array($userid, $supporterids)) {
            $kind = 0;
        }
        else {
            $kind = 1;
        }
        return $kind;
    }

    /**
     *
     * this method checks if there is a supporter saved and is active
     * if the actual user is no supporter we block the support function else not
     *
     **/

    public static function checkIfSupporters() {

        $repository = MUTicket_Util_Model::getSupporterRepository();
        $where = 'tbl.state = 1';
        $supporters = $repository->selectWhere($where);
        if (count($supporters)  > 0 && is_array($supporters)) {
            return 1;
        }
        else {
            $supporterIds = self::getSupporterIds();
            $supporterIds = explode(',', $supporterIds);
            $userid = UserUtil::getVar('uid');
            if (!in_array($userid, $supporterIds)) {
                return 0;
            } else {
                return 1;
            }
        }
    }
}
