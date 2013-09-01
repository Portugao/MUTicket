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
 * Utility implementation class for model helper methods.
 */
class MUTicket_Util_Model extends MUTicket_Util_Base_Model
{
	public function updateTicket($args) {

		$entity = $args['entity'];

		$serviceManager = ServiceUtil::getManager();
		$entityManager = $serviceManager->getService('doctrine.entitymanager');

		if ($entity == null) {
			System::redirect($redirecturl);
		}
		$entity->setRated(1);

		$entityManager->flush();

		return true;
	}

	/**
	 * This function closes a ticket called by dual window
	 * @param id   id of the ticket to close
	 */

	public function closeTicket($id) {

		// build ticket repository
		$repository = MUTicket_Util_Model::getTicketRepository();

		$entity = $repository->selectById($id);

		$serviceManager = ServiceUtil::getManager();
		$entityManager = $serviceManager->getService('doctrine.entitymanager');

		$entity->setState(0);

		$entityManager->flush();

		LogUtil::registerStatus($this->__('Done! The ticket is closed!'));

	}

	/**
	 *
	 * This method is for getting an array of the original uids
	 * for existing supporters or a single uid, if $id is set
	 *
	 * @ return array or int
	 */
	public static function getExistingSupporterUids($id = 0) {

		$repository = MUTicket_Util_Model::getSupporterRepository();

		if ($id == 0) {
			$supporters = $repository->selectWhere();
				
			$supporternames = array();

			foreach ($supporters as $supporter) {
				$supporternames[] = $supporter['username'];
			}

			$supporteruids = array();

			foreach ($supporternames as $supportername) {
				$supporteruids[] = UserUtil::getIdFromName($supportername);
			}
		}
		else {
			$supporter = $repository->selectById($id);
			$supportername = $supporter['username'];
			$supporteruids = UserUtil::getIdFromName($supportername);
		}

		return $supporteruids;
	}

	/**
	 *
	 * This method is for getting an array of supporter email addresses for
	 * supporters that are active
	 *
	 * @ return array
	 */
	public static function getSupporterMails($catid = '') {

		$repository = MUTicket_Util_Model::getSupporterRepository();

		$where = 'tbl.state = 1';

		$supporters = $repository->selectWhere($where);

		$supporternames = array();

		foreach ($supporters as $supporter) {
			if ($catid != '') {
				$supportercats = $supporter['supportcats'];
				$cats = unserialize($supportercats);
				foreach ($cats as $cat) {
					if ($cat == $catid) {
						$status = true;
						break;					

					}
					else {
						$status = false;
					}
				}
				if ($status == true) {
					$supporternames[] = $supporter['username'];
				}
			}
			else {
				$supporternames[] = $supporter['username'];
			}
		}
		
		// if no supporter is available with the relevant category
		// we take all supporters to send an email to
		if (empty($supporternames)) {
			foreach ($supporters as $supporter) {
				$supporternames[] = $supporter['username'];;
			}
		}

		$supporteruids = array();

		foreach ($supporternames as $supportername) {
			$supporteruids[] = UserUtil::getIdFromName($supportername);
		}
		$supportermailadresses = array();

		foreach ($supporteruids as $supporteruid) {
			$supportermailadresses[] = UserUtil::getVar('email', $supporteruid);
		}

		return $supportermailadresses;
	}

	/**
	 *
	 This method is for getting a repository for ratings
	 *
	 */

	public static function getRatingRepository() {

		$serviceManager = ServiceUtil::getManager();
		$entityManager = $serviceManager->getService('doctrine.entitymanager');
		$repository = $entityManager->getRepository('MUTicket_Entity_Rating');

		return $repository;
	}

	/**
	 *
	 This method is for getting a repository for tickets
	 *
	 */

	public static function getTicketRepository() {

		$serviceManager = ServiceUtil::getManager();
		$entityManager = $serviceManager->getService('doctrine.entitymanager');
		$repository = $entityManager->getRepository('MUTicket_Entity_Ticket');

		return $repository;
	}

	/**
	 *
	 This method is for getting a repository for supporters
	 *
	 */

	public static function getSupporterRepository() {

		$serviceManager = ServiceUtil::getManager();
		$entityManager = $serviceManager->getService('doctrine.entitymanager');
		$repository = $entityManager->getRepository('MUTicket_Entity_Supporter');

		return $repository;
	}
}