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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Thu Jan 05 16:12:14 CET 2012.
 */


/**
 * This is the Admin controller class providing navigation and interaction functionality.
 */
class MUTicket_Controller_Admin extends MUTicket_Controller_Base_Admin
{
	/**
	 * This method is the default function, and is called whenever the application's
	 * Admin area is called without defining arguments.
	 *
	 * @return mixed Output.
	 */
	public function main($args)
	{
		// DEBUG: permission check aspect starts
		$this->throwForbiddenUnless(SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_ADMIN));
		// DEBUG: permission check aspect ends


		// return viewtemplate
		return $this->redirect(ModUtil::url($this->name, 'admin', 'view', array('ot' => 'supporter')));

	}

	/**
	 * This method is the function for showing rating statistics of a supporter
	 *
	 */

	public function rating($args)
	{
		// DEBUG: permission check aspect starts
		$this->throwForbiddenUnless(SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_ADMIN));
		// DEBUG: permission check aspect ends

		$objectType = (isset($args['ot']) && !empty($args['ot'])) ? $args['ot'] : $this->request->getGet()->filter('ot', 'ticket', FILTER_SANITIZE_STRING);
		$rated = (isset($args['rated']) && !empty($args['rated'])) ? $args['rated'] : $this->request->getGet()->filter('rated', 0, FILTER_SANITIZE_STRING);
		$uid = (isset($args['supporter']) && !empty($args['supporter'])) ? $args['supporter'] : $this->request->getGet()->filter('supporter', 0, FILTER_SANITIZE_STRING);

		$supporteruid = MUTicket_Util_View::getExistingSupporterUids($uid);

		$supporterrepository = MUTicket_Util_Model::getSupporterRepository();

		$supporter = $supporterrepository->selectById($uid);
		$supportername = $supporter['username'];

		$repository = $this->entityManager->getRepository('MUTicket_Entity_' . ucfirst($objectType));

		// where clause for getting answers of this supporter
		$where = 'tbl.createdUserId = ' . DataUtil::formatForStore($supporteruid);

		$selectionArgs = array(
            'ot' => $objectType,
            'where' => $where,
            'orderBy' => $sort . ' ' . $sdir
		);

		// itemcount of tickets of supporter without pagination
		$counttickets = $repository->selectCount($where, $useJoins = true);

		// where clause for getting rated answers of this supporter
		$where2 = 'tbl.rated = 1';
		$where2 .= ' AND ';
		$where2 .= 'tbl.createdUserId = ' . DataUtil::formatForStore($supporteruid);

		$selectionArgs = array(
            'ot' => $objectType,
            'where' => $where,
            'orderBy' => $sort . ' ' . $sdir
		);

		// item list of rated tickets of supporter without pagination
		$entities = $repository->selectWhere($where2, $orderBy = '', $useJoins = true);
		
		$total = 0;
		
		foreach ($entities as $entity) {
			$total = $total + $entity['rating'][0]['ratingvalue'];
		}

		$objectCount = count($entities);

		$average = $total / $objectCount;
		$average = round($average, 1);
				
		// percent of rated tickets
		$percent = $objectCount / $counttickets * 100;
		$percent = round($percent, 2);
		
		// assign all to template
		$this->view->assign('items', $entities )
		->assign('counttickets', $counttickets)
		->assign('objectcount', $objectCount)
		->assign('percent', $percent)
		->assign('total', $total)		
		->assign('average', $average)		
		->assign('supporter', $supportername );

		// fetch and return the appropriate template
		return MUTicket_Util_View::processTemplate($this->view, 'admin', $objectType, 'rating', $args);
			
	}
}
