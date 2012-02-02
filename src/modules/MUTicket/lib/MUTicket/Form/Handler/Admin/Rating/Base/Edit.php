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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Wed Jan 11 12:10:27 CET 2012.
 */

/**
 * This handler class handles the page events of the Form called by the MUTicket_admin_edit() function.
 * It aims on the rating object type.
 *
 * More documentation is provided in the parent class.
 */
class MUTicket_Form_Handler_Admin_Rating_Base_Edit extends MUTicket_Form_Handler_Admin_Edit
{
    /**
     * Persistent member vars
     */

    /**
     * Pre-initialise hook.
     *
     * @return void
     */
    public function preInitialize()
    {
        parent::preInitialize();

        $this->objectType = 'rating';
        $this->objectTypeCapital = 'Rating';
        $this->objectTypeLower = 'rating';
        $this->objectTypeLowerMultiple = 'ratings';

        $this->hasPageLockSupport = true;
        $this->hasCategories = false;
    }

    /**
     * Initialize form handler.
     *
     * This method takes care of all necessary initialisation of our data and form states.
     *
     * @return boolean False in case of initialization errors, otherwise true.
     */
    public function initialize(Zikula_Form_View $view)
    {
        parent::initialize($view);

        $entity = $this->entityRef;

        if ($this->mode == 'edit') {
        } else {
            if ($this->hasTemplateId !== true) {
            }
        }

        // save parent identifiers of unidirectional incoming relationships
        $this->incomingIds['ticket'] = FormUtil::getPassedValue('ticket', '', 'GET');

        // save entity reference for later reuse
        $this->entityRef = $entity;

        // everything okay, no initialization errors occured
        return true;
    }

    /**
     * Post-initialise hook.
     *
     * @return void
     */
    public function postInitialize()
    {
        parent::postInitialize();
    }

    /**
     * Get list of allowed redirect codes.
     */
    protected function getRedirectCodes()
    {
        $codes = parent::getRedirectCodes();
        // admin list of tickets
        $codes[] = 'adminViewTicket';
        // admin display page of treated ticket
        $codes[] = 'adminDisplayTicket';
        // user list of tickets
        $codes[] = 'userViewTicket';
        // user display page of treated ticket
        $codes[] = 'userDisplayTicket';
        return $codes;
    }

    /**
     * Get the default redirect url. Required if no returnTo parameter has been supplied.
     * This method is called in handleCommand so we know which command has been performed.
     */
    protected function getDefaultReturnUrl($args, $obj)
    {
        // redirect to the list of ratings
        $viewArgs = array('ot' => $this->objectType);
        $url = ModUtil::url($this->name, 'admin', 'view', $viewArgs);

        if ($args['commandName'] != 'delete' && !($this->mode == 'create' && $args['commandName'] == 'cancel')) {
            // redirect to the detail page of treated rating
            $url = ModUtil::url($this->name, 'admin', 'display', array('ot' => 'rating', 'id' => $this->idValues['id']));
        }
        return $url;
    }

    /**
     * Command event handler.
     *
     * This event handler is called when a command is issued by the user.
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
        $result = parent::handleCommand($view, $args);
        if ($result === false) {
            return $result;
        }

        return $this->view->redirect($this->getRedirectUrl($args, $entity, $repeatCreateAction));
    }

    /**
     * Get success or error message for default operations.
     *
     * @param Array   $args    arguments from handleCommand method.
     * @param Boolean $success true if this is a success, false for default error.
     * @return String desired status or error message.
     */
    protected function getDefaultMessage($args, $success = false)
    {
        if ($success !== true) {
            return parent::getDefaultMessage($args, $success);
        }

        $message = '';
        switch ($args['commandName']) {
            case 'create':
                $message = $this->__('Done! Rating created.');
                break;
            case 'update':
                $message = $this->__('Done! Rating updated.');
                break;
            case 'update':
                $message = $this->__('Done! Rating deleted.');
                break;
        }
        return $message;
    }
    /**
     * Input data processing called by handleCommand method.
     */
    public function fetchInputData(Zikula_Form_View $view, &$args)
    {
        parent::fetchInputData($view, $args);

        // get treated entity reference from persisted member var
        $entity = $this->entityRef;

        $entityData = array();

        $this->reassignRelatedObjects();

        // assign fetched data
        if (count($entityData) > 0) {
            $entity->merge($entityData);
        }

        // save updated entity
        $this->entityRef = $entity;
    }
    /**
     * Executing insert and update statements
     *
     * @param Array   $args    arguments from handleCommand method.
     */
    public function performUpdate($args)
    {
        // get treated entity reference from persisted member var
        $entity = $this->entityRef;

        $this->updateRelationLinks($entity);
        //$this->entityManager->transactional(function($entityManager) {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        //});

        // save incoming relationship from parent entity
        if ($args['commandName'] == 'create') {
            if (!empty($this->incomingIds['ticket'])) {
                $relObj = ModUtil::apiFunc($this->name, 'selection', 'getEntity', array('ot' => 'ticket', 'id' => $this->incomingIds['ticket']));
                if ($relObj != null) {
                    $relObj->addRating($entity);
                }
            }
            $this->entityManager->flush();
        }
    }

    /**
     * Get url to redirect to.
     */
    protected function getRedirectUrl($args, $obj, $repeatCreateAction = false)
    {
        if ($this->inlineUsage == true) {
            $urlArgs = array('idp' => $this->idPrefix,
                'com' => $args['commandName']);
            $urlArgs = $this->addIdentifiersToUrlArgs($urlArgs);
            // inline usage, return to special function for closing the Zikula.UI.Window instance
            return ModUtil::url($this->name, 'admin', 'handleInlineRedirect', $urlArgs);
        }

        if ($repeatCreateAction) {
            return $this->repeatReturnUrl;
        }

        // normal usage, compute return url from given redirect code
        if (!in_array($this->returnTo, $this->getRedirectCodes())) {
            // invalid return code, so return the default url
            return $this->getDefaultReturnUrl($args, $obj);
        }

        // parse given redirect code and return corresponding url
        switch ($this->returnTo) {
            case 'admin':
                return ModUtil::url($this->name, 'admin', 'main');
            case 'adminView':
                return ModUtil::url($this->name, 'admin', 'view',
                    array('ot' => $this->objectType));
            case 'adminDisplay':
                if ($args['commandName'] != 'delete' && !($this->mode == 'create' && $args['commandName'] == 'cancel')) {
                    return ModUtil::url($this->name, 'admin', $this->addIdentifiersToUrlArgs());
                }
                return $this->getDefaultReturnUrl($args, $obj);
            case 'user':
                return ModUtil::url($this->name, 'user', 'main');
            case 'userView':
                return ModUtil::url($this->name, 'user', 'view',
                    array('ot' => $this->objectType));
            case 'userDisplay':
                if ($args['commandName'] != 'delete' && !($this->mode == 'create' && $args['commandName'] == 'cancel')) {
                    return ModUtil::url($this->name, 'user', $this->addIdentifiersToUrlArgs());
                }
                return $this->getDefaultReturnUrl($args, $obj);
            case 'account':
                return ModUtil::url($this->name, 'account', 'main');
            case 'adminViewTicket':
                return ModUtil::url($this->name, 'admin', 'view',
                    array('ot' => 'ticket'));
            case 'adminDisplayTicket':
                if (!empty($this->ticket)) {
                    return ModUtil::url($this->name, 'admin', 'display', array('ot' => 'ticket', 'id' => $this->ticket));
                }
                return $this->getDefaultReturnUrl($args, $obj);
            case 'userViewTicket':
                return ModUtil::url($this->name, 'user', 'view',
                    array('ot' => 'ticket'));
            case 'userDisplayTicket':
                if (!empty($this->ticket)) {
                    return ModUtil::url($this->name, 'user', 'display', array('ot' => 'ticket', 'id' => $this->ticket));
                }
                return $this->getDefaultReturnUrl($args, $obj);
            default:
                return $this->getDefaultReturnUrl($args, $obj);
        }
    }

    /**
     * Reassign options chosen by the user to avoid unwanted form state resets.
     * Necessary until issue #23 is solved.
     */
    public function reassignRelatedObjects()
    {
        $selectedRelations = array();
        $this->view->assign('selectedRelations', $selectedRelations);
    }
    /**
     * Helper method for updating links to related records.
     */
    protected function updateRelationLinks($entity)
    {
    }
}