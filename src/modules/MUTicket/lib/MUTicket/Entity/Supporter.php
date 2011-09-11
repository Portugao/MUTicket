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
 * @version Generated by ModuleStudio 0.5.2 (http://modulestudio.de) at Sun Sep 11 16:08:57 CEST 2011.
 */

use Doctrine\ORM\Mapping as ORM;
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
    // feel free to add your own methods here



    /**
     * Post-Process the data after the entity has been constructed by the entity manager.
     * The event happens after the entity has been loaded from database or after a refresh call.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - no access to associations (not initialised yet)
     *
     * @ORM\PostLoad
     * @return void.
     */
    public function postLoadCallback()
    {
        // echo 'loaded a record ...';

        $currentType = FormUtil::getPassedValue('type', 'user', 'GETPOST', FILTER_SANITIZE_STRING);
        $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);

        $this['id'] = (int) ((isset($this['id']) && !empty($this['id'])) ? DataUtil::formatForDisplay($this['id']) : 0);
    if ($currentFunc != 'edit') {
        $this['username'] = ((isset($this['username']) && !empty($this['username'])) ? DataUtil::formatForDisplayHTML($this['username']) : '');
    }
    if ($currentFunc != 'edit') {
        $this['supportcats'] = ((isset($this['supportcats']) && !empty($this['supportcats'])) ? DataUtil::formatForDisplayHTML($this['supportcats']) : '');
    }

    }

    /**
     * Pre-Process the data prior to an insert operation.
     * The event happens before the entity managers persist operation is executed for this entity.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - no identifiers available if using an identity generator like sequences
     *     - Doctrine won't recognize changes on relations which are done here
     *       if this method is called by cascade persist
     *     - no creation of other entities allowed
     *
     * @ORM\PrePersist
     * @return void.
     */
    public function prePersistCallback()
    {
        // echo 'inserting a record ...';
        $this->validate();
    }

    /**
     * Post-Process the data after an insert operation.
     * The event happens after the entity has been made persistant.
     * Will be called after the database insert operations.
     * The generated primary key values are available.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *
     * @ORM\PostPersist
     * @return void.
     */
    public function postPersistCallback()
    {
        // echo 'inserted a record ...';
    }

    /**
     * Pre-Process the data prior a delete operation.
     * The event happens before the entity managers remove operation is executed for this entity.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL DELETE statement
     *
     * @ORM\PreRemove
     * @return void.
     */
    public function preRemoveCallback()
    {
/*        // delete workflow for this entity
        $result = Zikula_Workflow_Util::deleteWorkflow($this);
        if ($result === false) {
            $dom = ZLanguage::getModuleDomain('MUTicket');
            return LogUtil::registerError(__('Error! Could not remove stored workflow.', $dom));
        }*/
    }

    /**
     * Post-Process the data after a delete.
     * The event happens after the entity has been deleted.
     * Will be called after the database delete operations.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL DELETE statement
     *
     * @ORM\PostRemove
     * @return void
     */
    public function postRemoveCallback()
    {
        // echo 'deleted a record ...';
    }

    /**
     * Pre-Process the data prior to an update operation.
     * The event happens before the database update operations for the entity data.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL UPDATE statement
     *     - changes on associations are not allowed and won't be recognized by flush
     *     - changes on properties won't be recognized by flush as well
     *     - no creation of other entities allowed
     *
     * @ORM\PreUpdate
     * @return void.
     */
    public function preUpdateCallback()
    {
        // echo 'updating a record ...';
        $this->validate();
    }

    /**
     * Post-Process the data after an update operation.
     * The event happens after the database update operations for the entity data.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL UPDATE statement
     *
     * @ORM\PostUpdate
     * @return void.
     */
    public function postUpdateCallback()
    {
        // echo 'updated a record ...';
    }

    /**
     * Pre-Process the data prior to a save operation.
     * This combines the PrePersist and PreUpdate events.
     * For more information see corresponding callback handlers.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void.
     */
    public function preSaveCallback()
    {
        // echo 'saving a record ...';
        $this->validate();
    }

    /**
     * Post-Process the data after a save operation.
     * This combines the PostPersist and PostUpdate events.
     * For more information see corresponding callback handlers.
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     * @return void.
     */
    public function postSaveCallback()
    {
        // echo 'saved a record ...';
    }

}
