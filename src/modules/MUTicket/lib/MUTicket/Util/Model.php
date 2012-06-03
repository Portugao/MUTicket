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
 * Utility implementation class for model helper methods.
 */
class MUTicket_Util_Model extends Zikula_AbstractBase
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
}
