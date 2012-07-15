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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for supporter entities.
 * @ORM\Entity(repositoryClass="MUTicket_Entity_Repository_Supporter")
 * @ORM\Table(name="muticket_supporter")
 * @ORM\HasLifecycleCallbacks
 */
class MUTicket_Entity_Supporter extends MUTicket_Entity_Base_Supporter
{
	/**
	 * Collect available actions for this entity.
	 * TODO links don't work correctly
	 */
	protected function prepareItemActions()
	{
		if (!empty($this->_actions)) {
			return;
		}

		$currentType = FormUtil::getPassedValue('type', 'user', 'GETPOST', FILTER_SANITIZE_STRING);
		$currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);
		$dom = ZLanguage::getModuleDomain('MUTicket');
		if ($currentType == 'admin') {
			if (in_array($currentFunc, array('main', 'view'))) {
				$this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'rating', 'arguments' => array('ot' => 'ticket', 'rated' => 1, 'supporter' => $this['id'])),
                        'icon' => 'display',
                        'linkTitle' => __('Call statistic and rated tickets of this supporter', $dom),
                        'linkText' => __('Rating statictics', $dom)
				);
				/* $this->_actions[] = array(
				 'url' => array('type' => 'admin', 'func' => 'display', 'arguments' => array('ot' => 'supporter', 'id' => $this['id'])),
				 'icon' => 'display',
				 'linkTitle' => str_replace('"', '', $this['username']),
				 'linkText' => __('Details', $dom)
				 );*/
			}

			if (in_array($currentFunc, array('main', 'view', 'display'))) {
				if (SecurityUtil::checkPermission('MUTicket::', '.*', ACCESS_EDIT)) {

					$this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'edit', 'arguments' => array('ot' => 'supporter', 'id' => $this['id'])),
                        'icon' => 'edit',
                        'linkTitle' => __('Edit', $dom),
                        'linkText' => __('Edit', $dom)
					);
					/* $this->_actions[] = array(
					 'url' => array('type' => 'admin', 'func' => 'edit', 'arguments' => array('ot' => 'supporter', 'astemplate' => $this['id'])),
					 'icon' => 'saveas',
					 'linkTitle' => __('Reuse for new item', $dom),
					 'linkText' => __('Reuse', $dom)
					 );*/
				}
				if (SecurityUtil::checkPermission('MUTicket::', '.*', ACCESS_DELETE)) {
					$this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'delete', 'arguments' => array('ot' => 'supporter', 'id' => $this['id'])),
                        'icon' => 'delete',
                        'linkTitle' => __('Delete', $dom),
                        'linkText' => __('Delete', $dom)
					);
				}
			}
			if ($currentFunc == 'display') {
				/* $this->_actions[] = array(
				 'url' => array('type' => 'admin', 'func' => 'view', 'arguments' => array('ot' => 'supporter')),
				 'icon' => 'back',
				 'linkTitle' => __('Back to overview', $dom),
				 'linkText' => __('Back to overview', $dom)
				 );*/
			}
		}
		if ($currentType == 'user') {
			if (in_array($currentFunc, array('main', 'view'))) {
				/* $this->_actions[] = array(
				 'url' => array('type' => 'user', 'func' => 'display', 'arguments' => array('ot' => 'supporter', 'id' => $this['id'])),
				 'icon' => 'display',
				 'linkTitle' => str_replace('"', '', $this['username']),
				 'linkText' => __('Details', $dom)
				 );*/
			}

			if (in_array($currentFunc, array('main', 'view', 'display'))) {
				if (SecurityUtil::checkPermission('MUTicket::', '.*', ACCESS_EDIT)) {

					$this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'edit', 'arguments' => array('ot' => 'supporter', 'id' => $this['id'])),
                        'icon' => 'edit',
                        'linkTitle' => __('Edit', $dom),
                        'linkText' => __('Edit', $dom)
					);
					/* $this->_actions[] = array(
					 'url' => array('type' => 'user', 'func' => 'edit', 'arguments' => array('ot' => 'supporter', 'astemplate' => $this['id'])),
					 'icon' => 'saveas',
					 'linkTitle' => __('Reuse for new item', $dom),
					 'linkText' => __('Reuse', $dom)
					 );*/
				}
				if (SecurityUtil::checkPermission('MUTicket::', '.*', ACCESS_DELETE)) {
					$this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'delete', 'arguments' => array('ot' => 'supporter', 'id' => $this['id'])),
                        'icon' => 'delete',
                        'linkTitle' => __('Delete', $dom),
                        'linkText' => __('Delete', $dom)
					);
				}
			}
			if ($currentFunc == 'display') {
				/* $this->_actions[] = array(
				 'url' => array('type' => 'user', 'func' => 'view', 'arguments' => array('ot' => 'supporter')),
				 'icon' => 'back',
				 'linkTitle' => __('Back to overview', $dom),
				 'linkText' => __('Back to overview', $dom)
				 );*/
			}
		}
	}



	/**
	 * Post-Process the data after the entity has been constructed by the entity manager.
	 *
	 * @ORM\PostLoad
	 * @see MUTicket_Entity_Base_Supporter::performPostLoadCallback()
	 * @return void.
	 */
	public function postLoadCallback()
	{
		$request = new Zikula_Request_Http();
		$type = $request->getGet()->filter('type', 'admin', FILTER_SANITIZE_STRING);
		$func = $request->getGet()->filter('func', 'view', FILTER_SANITIZE_STRING);
		$ot = $request->getGet()->filter('ot', 'ticket', FILTER_SANITIZE_STRING);

		if ($type == 'admin') {
			if ($ot == 'supporter') {
				 
				$this->getSupportcats();
				$supportcats = $this->supportcats;
				 
				if ($func == 'view') {					 
					$supportcats = urldecode($supportcats);
					$cats = unserialize($supportcats);
					$this->setSupportcats($cats);
				}
				if ($func == 'edit') {
					$cats = unserialize($supportcats);
					$supportcats = urldecode($supportcats);
					$this->setSupportcats($cats);
				}
			}
		}
		$this->performPostLoadCallback();
	}

	/**
	 * Pre-Process the data prior to an insert operation.
	 *
	 * @ORM\PrePersist
	 * @see MUTicket_Entity_Base_Supporter::performPrePersistCallback()
	 * @return void.
	 */
	public function prePersistCallback()
	{
		$request = new Zikula_Request_Http();
		$type = $request->getGet()->filter('type', 'admin', FILTER_SANITIZE_STRING);
		$func = $request->getGet()->filter('func', 'view', FILTER_SANITIZE_STRING);
		$ot = $request->getGet()->filter('ot', 'ticket', FILTER_SANITIZE_STRING);
		 
		if ($type == 'admin' && $func == 'edit' && $ot == 'supporter') {
			$this->getSupportcats();
			$supportercats = $this->supportcats;
			$cats = serialize($supportercats);
			$this->setSupportcats($cats);
		}
		$this->performPrePersistCallback();
	}

	/**
	 * Post-Process the data after an insert operation.
	 *
	 * @ORM\PostPersist
	 * @see MUTicket_Entity_Base_Supporter::performPostPersistCallback()
	 * @return void.
	 */
	public function postPersistCallback()
	{
		$this->performPostPersistCallback();
	}

	/**
	 * Pre-Process the data prior a delete operation.
	 *
	 * @ORM\PreRemove
	 * @see MUTicket_Entity_Base_Supporter::performPreRemoveCallback()
	 * @return void.
	 */
	public function preRemoveCallback()
	{
		$this->performPreRemoveCallback();
	}

	/**
	 * Post-Process the data after a delete.
	 *
	 * @ORM\PostRemove
	 * @see MUTicket_Entity_Base_Supporter::performPostRemoveCallback()
	 * @return void
	 */
	public function postRemoveCallback()
	{
		$this->performPostRemoveCallback();
	}

	/**
	 * Pre-Process the data prior to an update operation.
	 *
	 * @ORM\PreUpdate
	 * @see MUTicket_Entity_Base_Supporter::performPreUpdateCallback()
	 * @return void.
	 */
	public function preUpdateCallback()
	{
		$request = new Zikula_Request_Http();
		$type = $request->getGet()->filter('type', 'admin', FILTER_SANITIZE_STRING);
		$func = $request->getGet()->filter('func', 'view', FILTER_SANITIZE_STRING);
		$ot = $request->getGet()->filter('ot', 'ticket', FILTER_SANITIZE_STRING);
		 
		if ($type == 'admin' && $func == 'edit' && $ot == 'supporter') {
			$this->getSupportcats();
			$supportercats = $this->supportcats;
			$cats = serialize($supportercats);
			$this->setSupportcats($cats);
		}
		$this->performPreUpdateCallback();
	}

	/**
	 * Post-Process the data after an update operation.
	 *
	 * @ORM\PostUpdate
	 * @see MUTicket_Entity_Base_Supporter::performPostUpdateCallback()
	 * @return void.
	 */
	public function postUpdateCallback()
	{
		$this->performPostUpdateCallback();
	}

	/**
	 * Pre-Process the data prior to a save operation.
	 *
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 * @see MUTicket_Entity_Base_Supporter::performPreSaveCallback()
	 * @return void.
	 */
	public function preSaveCallback()
	{
		$this->performPreSaveCallback();
	}

	/**
	 * Post-Process the data after a save operation.
	 *
	 * @ORM\PostPersist
	 * @ORM\PostUpdate
	 * @see MUTicket_Entity_Base_Supporter::performPostSaveCallback()
	 * @return void.
	 */
	public function postSaveCallback()
	{
		$this->performPostSaveCallback();
	}

}
