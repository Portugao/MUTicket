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
 * Validator class for encapsulating entity validation methods.
 *
 * This is the base validation class for rating entities.
 */
class MUTicket_Entity_Validator_Base_Rating extends MUTicket_Validator
{


    /**
     * Performs all validation rules.
     *
     * @return mixed either array with error information or true on success
     */
    public function validateAll()
    {
        $errorInfo = array('message' => '', 'code' => 0, 'debugArray' => array());
        $dom = ZLanguage::getModuleDomain('MUTicket');
        if (!$this->isValidInteger('ratingvalue')) {
            $errorInfo['message'] = __f('Error! Field value may only contain digits (%s).', array('ratingvalue'), $dom);
            return $errorInfo;
        }
        if (!$this->isNumberNotEmpty('ratingvalue')) {
            $errorInfo['message'] = __f('Error! Field value must not be 0 (%s).', array('ratingvalue'), $dom);
            return $errorInfo;
        }
        if (!$this->isNumberNotLongerThan('ratingvalue', 2)) {
            $errorInfo['message'] = __f('Error! Length of field value must not be higher than %2$s (%1$s).', array('ratingvalue', 2), $dom);
            return $errorInfo;
        }
        return true;
    }


    /**
     * Check for unique values.
     *
     * This method determines if there already exist ratings with the same rating.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check, true if the given rating does not already exist
     */
    public function isUniqueValue($fieldName)
    {
        if (empty($this->entity[$fieldName])) {
            return false;
        }

        $serviceManager = ServiceUtil::getManager();
        $entityManager = $serviceManager->getService('doctrine.entitymanager');
        $repository = $entityManager->getRepository('MUTicket_Entity_Rating');

        $excludeid = $this->entity['id'];
        return $repository->detectUniqueState($fieldName, $this->entity[$fieldName], $excludeid);
    }

    /**
     * Get entity.
     *
     * @return Zikula_EntityAccess
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entity.
     *
     * @param Zikula_EntityAccess $entity.
     *
     * @return void
     */
    public function setEntity(Zikula_EntityAccess $entity = null)
    {
        $this->entity = $entity;
    }


}
