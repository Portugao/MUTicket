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
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for rating entities.
 * @ORM\Entity(repositoryClass="MUTicket_Entity_Repository_Rating")
 * @ORM\Table(name="muticket_rating")
 * @ORM\HasLifecycleCallbacks
 */
class MUTicket_Entity_Rating extends MUTicket_Entity_Base_Rating
{
    // feel free to add your own methods here



    /**
     * Post-Process the data after the entity has been constructed by the entity manager.
     *
     * @ORM\PostLoad
     * @see MUTicket_Entity_Base_Rating::performPostLoadCallback()
     * @return void.
     */
    public function postLoadCallback()
    {
        $this->performPostLoadCallback();
    }

    /**
     * Pre-Process the data prior to an insert operation.
     *
     * @ORM\PrePersist
     * @see MUTicket_Entity_Base_Rating::performPrePersistCallback()
     * @return void.
     */
    public function prePersistCallback()
    {    	
        $this->performPrePersistCallback();
    }

    /**
     * Post-Process the data after an insert operation.
     *
     * @ORM\PostPersist
     * @see MUTicket_Entity_Base_Rating::performPostPersistCallback()
     * @return void.
     */
    public function postPersistCallback()
    {
    	$args['entity'] = $this->getTicket();
    	MUTicket_Util_Model::updateTicket($args);
        $this->performPostPersistCallback();
    }

    /**
     * Pre-Process the data prior a delete operation.
     *
     * @ORM\PreRemove
     * @see MUTicket_Entity_Base_Rating::performPreRemoveCallback()
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
     * @see MUTicket_Entity_Base_Rating::performPostRemoveCallback()
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
     * @see MUTicket_Entity_Base_Rating::performPreUpdateCallback()
     * @return void.
     */
    public function preUpdateCallback()
    {
        $this->performPreUpdateCallback();
    }

    /**
     * Post-Process the data after an update operation.
     *
     * @ORM\PostUpdate
     * @see MUTicket_Entity_Base_Rating::performPostUpdateCallback()
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
     * @see MUTicket_Entity_Base_Rating::performPreSaveCallback()
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
     * @see MUTicket_Entity_Base_Rating::performPostSaveCallback()
     * @return void.
     */
    public function postSaveCallback()
    {
        $this->performPostSaveCallback();
    }

}
