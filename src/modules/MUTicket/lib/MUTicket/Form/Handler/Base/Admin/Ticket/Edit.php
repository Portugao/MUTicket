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

/**
 * This handler class handles the page events of the Form called by the MUTicket_admin_edit() function.
 * It aims on the ticket object type.
 *
 * Member variables in a form handler object are persisted accross different page requests. This means
 * a member variable $this->X can be set on one request and on the next request it will still contain
 * the same value.
 *
 * A form handler will be notified of various events that happens during it's life-cycle.
 * When a specific event occurs then the corresponding event handler (class method) will be executed. Handlers
 * are named exactly like their events - this is how the framework knows which methods to call.
 *
 * The list of events is:
 *
 * - <b>initialize</b>: this event fires before any of the events for the plugins and can be used to setup
 *   the form handler. The event handler typically takes care of reading URL variables, access control
 *   and reading of data from the database.
 *
 * - <b>handleCommand</b>: this event is fired by various plugins on the page. Typically it is done by the
 *   Zikula_Form_Plugin_Button plugin to signal that the user activated a button.
 */
class MUTicket_Form_Handler_Base_Admin_Ticket_Edit extends Zikula_Form_AbstractHandler
{
    /**
     * Persistent member vars
     */

    /**
     * @var string Name of treated object type.
     */
    protected $objectType;

    /**
     * @var MUTicket_Entity_Ticket Reference to treated ticket instance.
     */
    protected $ticketRef = false;

    /**
     * @var array List of identifiers of treated ticket.
     */
    protected $idValues = array();

    /**
     * @var string One of "create" or "edit".
     */
    protected $mode;

    /**
     * @var string Code defining the redirect goal after command handling.
     */
    protected $returnTo = null;

    /**
     * @var string Url of current form with all parameters for multiple creations.
     */
    protected $repeatReturnUrl = null;

    /**
     * @var string Whether this form is being used inline within a window.
     */
    protected $inlineUsage = false;

    /**
     * @var string Full prefix for related items.
     */
    protected $idPrefix = '';

    /**
     * Post construction hook.
     *
     * @return mixed
     */
    public function setup()
    {
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
        $this->inlineUsage = ((UserUtil::getTheme() == 'Printer') ? true : false);
        $this->idPrefix = $this->request->getGet()->filter('idp', '', FILTER_SANITIZE_STRING);

        // initialise redirect goal
        $this->returnTo = $this->request->getGet()->filter('returnTo', null, FILTER_SANITIZE_STRING);
        // store current uri for repeated creations
        $this->repeatReturnUrl = System::getCurrentURI();

        $this->objectType = 'ticket';

        $entityClass = 'MUTicket_Entity_' . ucfirst($this->objectType);
        $repository = $this->entityManager->getRepository($entityClass);

        $objectTemp = new $entityClass();
        $idFields = $objectTemp->get_idFields();

        // retrieve identifier of the object we wish to view
        $this->idValues = MUTicket_Util_Controller::retrieveIdentifier($this->request, array(), $this->objectType, $idFields);
        $hasIdentifier = MUTicket_Util_Controller::isValidIdentifier($this->idValues);

        $entity = null;
        $this->mode = ($hasIdentifier) ? 'edit' : 'create';

        if ($this->mode == 'edit') {
            if (!SecurityUtil::checkPermission('MUTicket:Ticket:', '::', ACCESS_EDIT)) {
                // set an error message and return false
                return $this->view->setErrorMsg(LogUtil::registerPermissionError());
            }

        $entity = $repository->selectById($this->idValues);
        if (!is_object($entity) || !isset($entity[$idFields[0]])) {
            return $this->view->setErrorMsg($this->__('No such item.'));
        }

            // try to guarantee that only one person at a time can be editing this ticket
            $itemID = '';
            foreach ($idFields as $idField) {
                if (!empty($itemID)) {
                    $itemID .= '_';
                }
                $itemID .= $this->idValues[$idField];
            }
            $returnUrl = ModUtil::url('MUTicket', 'admin', 'display', array('ot' => 'ticket', 'id' => $this->idValues['id']));
            ModUtil::apiFunc('PageLock', 'user', 'pageLock',
                                 array('lockName' => 'MUTicketTicket' . $itemID,
                                       'returnUrl' => $returnUrl));
        }
        else {
            if (!SecurityUtil::checkPermission('MUTicket:Ticket:', '::', ACCESS_ADD)) {
                return $this->view->setErrorMsg(LogUtil::registerPermissionError());
            }

            $hasTemplateId = false;
            $templateId = $this->request->getGet()->get('astemplate', '');
            if (!empty($templateId)) {
                $templateIdValueParts = explode('_', $templateId);
                $hasTemplateId = (count($templateIdValueParts) == count($idFields));
            }
            if ($hasTemplateId === true) {
                $templateIdValues = array();
                $i = 0;
                foreach ($idFields as $idField) {
                    $templateIdValues[$idField] = $templateIdValueParts[$i];
                    $i++;
                }
                // reuse existing ticket
        $entity = $repository->selectById($templateIdValues);
        if (!is_object($entity) || !isset($entity[$idFields[0]])) {
            return $this->view->setErrorMsg($this->__('No such item.'));
        }
                //foreach ($idFields as $idField) {
                //     $entity[$idField] = null;
                //}
            }
            else {
                $entity = new $entityClass();

                $entity['parent'] = $this->retrieveRelatedObjects('ticket', 'parent', false);
                $entity['rating'] = $this->retrieveRelatedObjects('rating', 'rating', true);
                $entity['children'] = $this->retrieveRelatedObjects('ticket', 'children', true);
            }
        }

        $entityData = $entity->toArray();

        // assign data to template as array for easy translatable support
        $this->view->assign('ticket', $entityData)
                   // assign also the actual object for categories listener
                   ->assign('ticketObj', $entity)
                   ->assign('mode', $this->mode)
                   ->assign('inlineUsage', $this->inlineUsage);

        // save entity reference for reuse in handleCommand method
        $this->ticketRef = $entity;

        // load and assign registered categories
        $categories = CategoryRegistryUtil::getRegisteredModuleCategories('MUTicket', 'Ticket', 'id');
        $this->view->assign('registries', $categories);

        $this->initializeAdditions();

        // everything okay, no initialization errors occured
        return true;
    }

    /**
     * Method stub for own additions in subclasses.
     */
    protected function initializeAdditions()
    {
    }

    /**
     * Pre-initialise hook.
     *
     * @return void
     */
    public function preInitialize()
    {
    }

    /**
     * Post-initialise hook.
     *
     * @return void
     */
    public function postInitialize()
    {
    }

    /**
     * Get list of allowed redirect codes.
     */
    protected function getRedirectCodes()
    {
        $codes = array();
        // main page of admin area
        $codes[] = 'admin';
        // admin list of tickets
        $codes[] = 'adminView';
        // admin display page of treated ticket
        $codes[] = 'adminDisplay';
        // main page of user area
        $codes[] = 'user';
        // user list of tickets
        $codes[] = 'userView';
        // user display page of treated ticket
        $codes[] = 'userDisplay';
        // main page of ajax area
        $codes[] = 'ajax';
        // main page of account area
        $codes[] = 'account';
        return $codes;
    }

    /**
     * Get the default redirect url. Required if no returnTo parameter has been supplied.
     * This method is called in handleCommand so we know which command has been performed.
     */
    protected function getDefaultReturnUrl($args, $obj)
    {
        // redirect to the list of tickets
        $viewArgs = array('ot' => $this->objectType);
        $url = ModUtil::url('MUTicket', 'admin', 'view', $viewArgs);

        if ($args['commandName'] != 'delete') {
            // redirect to the detail page of treated ticket
            $url = ModUtil::url('MUTicket', 'admin', 'display', array('ot' => 'ticket', 'id' => $this->idValues['id']));
        }
        return $url;
    }

    /**
     * Command event handler.
     *
     * This event handler is called when a command is issued by the user. Commands are typically something
     * that originates from a {@link Zikula_Form_Plugin_Button} plugin. The passed args contains different properties
     * depending on the command source, but you should at least find a <var>$args['commandName']</var>
     * value indicating the name of the command. The command name is normally specified by the plugin
     * that initiated the command.
     * @see Zikula_Form_Plugin_Button
     * @see Zikula_Form_Plugin_ImageButton
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
        if ($args['commandName'] == 'delete') {
            if (!SecurityUtil::checkPermission('MUTicket:Ticket:', '::', ACCESS_DELETE)) {
                return $this->view->setErrorMsg(LogUtil::registerPermissionError());
            }
        }

        $selectedRelations = array();
        // reassign the ticket eventually chosen by the user
        $selectedRelations['parent'] = $this->retrieveRelatedObjects('ticket', 'muticketTicket_ParentItemList', false, 'POST');
        // reassign the tickets eventually chosen by the user
        $selectedRelations['rating'] = $this->retrieveRelatedObjects('rating', 'muticketRating_RatingItemList', true, 'POST');
        // reassign the tickets eventually chosen by the user
        $selectedRelations['children'] = $this->retrieveRelatedObjects('ticket', 'muticketTicket_ChildrenItemList', true, 'POST');
        $this->view->assign('selectedRelations', $selectedRelations);

        if (!in_array($args['commandName'], array('delete', 'cancel'))) {
            // do forms validation including checking all validators on the page to validate their input
            if (!$this->view->isValid()) {
                return false;
            }
        }

        $this->objectType = 'ticket';

        $entityClass = 'MUTicket_Entity_' . ucfirst($this->objectType);
        $repository = $this->entityManager->getRepository($entityClass);

        // get treated entity reference from persisted member var
        $ticket = $this->ticketRef;

        // fetch posted data input values as an associative array
        $formData = $this->view->getValues();
        // we want the array with our field values
        $ticketData = $formData['ticket'];
        unset($formData['ticket']);

        $ticketData['Parent'] = ((isset($selectedRelations['parent'])) ? $selectedRelations['parent'] : $this->retrieveRelatedObjects('ticket', 'muticketTicket_ParentItemList', false, 'POST'));

        if (in_array($args['commandName'], array('create', 'update'))) {
            $ticketData = $this->handleUploads($ticketData, $ticket);
            if ($ticketData == false) {
                return false;
            }
        }

        $repeatCreateAction = false;
        if ($args['commandName'] == 'create' && isset($ticketData['repeatcreation'])) {
            $repeatCreateAction = $ticketData['repeatcreation'];
            unset($ticketData['repeatcreation']);
        }

        // assign fetched data
        $ticket->merge($ticketData);

        if (in_array($args['commandName'], array('create', 'update'))) {
            // event handling if user clicks on create or update

            // Let any hooks perform additional validation actions
            $hook = new Zikula_ValidationHook('muticket.ui_hooks.tickets.validate_edit', new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            // TODO validation incomplete (#36)

            // save ticket
            $successMessage = '';
            $this->updateRelationLinks($ticket);
            //$this->entityManager->transactional(function($entityManager) {
                $this->entityManager->persist($ticket);
                $this->entityManager->flush();
            //});

            if ($args['commandName'] == 'create') {
                $successMessage = $this->__('Done! Ticket created.');
            } else if ($args['commandName'] == 'update') {
                $successMessage = $this->__('Done! Ticket updated.');
            }

            if ($args['commandName'] == 'create') {
                // store new identifier
                $this->idValues['id'] = $ticket->getId();
                // check if the insert has worked, might become obsolete due to exception usage
                if (!$this->idValues['id']) {
                    return $this->view->setErrorMsg($this->__('Error! Creation attempt failed.'));
                }
            } else if ($args['commandName'] == 'update') {
            }

            LogUtil::registerStatus($successMessage);

            // Let any hooks know that we have created or updated an item
            $url = new Zikula_ModUrl('MUTicket', 'admin', 'display', ZLanguage::getLanguageCode(), array('ot' => $this->objectType, 'id' => $this->idValues['id']));
            $hook = new Zikula_ProcessHook('muticket.ui_hooks.tickets.process_edit', $this->id, $url);
            $this->notifyHooks($hook);
        } else if ($args['commandName'] == 'delete') {
            // event handling if user clicks on delete

            // Let any hooks perform additional validation actions
            $hook = new Zikula_ValidationHook('muticket.ui_hooks.tickets.validate_delete', new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            // TODO validation incomplete (#36)

            // delete ticket
            $this->entityManager->remove($ticket);
            $this->entityManager->flush();


            LogUtil::registerStatus($this->__('Done! Ticket deleted.'));

            // Let any hooks know that we have deleted an item
            $hook = new Zikula_ProcessHook('muticket.ui_hooks.tickets.process_delete', $this->id);
            $this->notifyHooks($hook);
        } else if ($args['commandName'] == 'cancel') {
            // event handling if user clicks on cancel
        }

        if ($args['commandName'] != 'cancel') {
            // clear view cache to reflect our changes
            $this->view->clear_cache();
        }

        if ($this->mode == 'edit') {
            ModUtil::apiFunc('PageLock', 'user', 'releaseLock',
                             array('lockName' => 'MUTicketTicket' . $this->id));
        }
        return $this->view->redirect($this->getRedirectUrl($args, $ticket, $repeatCreateAction));

        // We should in principle not end here at all, since the above command handlers should
        // match all possible commands, but we return "ok" (true) for all cases.
        // You could also return $this->view->setErrorMsg('Unexpected command')
        return true;
    }

    /**
     * Get url to redirect to.
     */
    protected function getRedirectUrl($args, $obj, $repeatCreateAction = false)
    {
        if ($this->inlineUsage == true) {
            // inline usage, return to special function for closing the Zikula.UI.Window instance
            return ModUtil::url('MUTicket', 'admin', 'handleInlineRedirect',
                                                                 array('idp' => $this->idPrefix,
                                                                       'id' => $this->idValues['id'],
                                                                       'com' => $args['commandName']));
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
                                    return ModUtil::url('MUTicket', 'admin');
            case 'adminView':
                                    return ModUtil::url('MUTicket', 'admin', 'view',
                                                             array('ot' => $this->objectType));
            case 'adminDisplay':
                                    if ($args['commandName'] != 'delete') {
                                        return ModUtil::url('MUTicket', 'admin', 'display', array('ot' => 'ticket', 'id' => $this->idValues['id']));
                                    }
                                    return $this->getDefaultReturnUrl($args, $obj);
            case 'user':
                                    return ModUtil::url('MUTicket', 'user');
            case 'userView':
                                    return ModUtil::url('MUTicket', 'user', 'view',
                                                             array('ot' => $this->objectType));
            case 'userDisplay':
                                    if ($args['commandName'] != 'delete') {
                                        return ModUtil::url('MUTicket', 'user', 'display', array('ot' => 'ticket', 'id' => $this->idValues['id']));
                                    }
                                    return $this->getDefaultReturnUrl($args, $obj);
            case 'account':
                                    return ModUtil::url('MUTicket', 'account');
            default:
                                    return $this->getDefaultReturnUrl($args, $obj);
        }
    }

    /**
     * Select a related object in create mode.
     *
     * @return array Single result or list of results.
     */
    protected function retrieveRelatedObjects($objectType, $relationInputFieldName, $many = false, $source = 'GET')
    {
        $entityClass = 'MUTicket_Entity_' . ucfirst($objectType);
        $repository = $this->entityManager->getRepository($entityClass);

        $objectTemp = new $entityClass(); 
        $idFields = $objectTemp->get_idFields();

        $where = '';
        $sortParam = $repository->getDefaultSortingField() . ' asc';

        $inputValue = '';
        if ($source == 'POST') {
            $inputValue = $this->request->getPost()->get($relationInputFieldName, '');
        }
        else {
            $inputValue = $this->request->getGet()->get($relationInputFieldName, '');
        }
        if (empty($inputValue)) {
            return $many ? array() : null;
        }

        $inputValueParts = explode('_', $inputValue);
        $i = 0;
        foreach ($idFields as $idField) {
            if (!empty($where)) {
                $where .= ' AND ';
            }

            if ($many) {
                $where .= 'tbl.' . $idField . ' IN (' . DataUtil::formatForStore($inputValueParts[$i]) . ')';
            } else {
                $where .= 'tbl.' . $idField . ' = \'' . DataUtil::formatForStore($inputValueParts[$i]) . '\'';
            }
            $i++;
        }
        list($result, $resultCount) = $repository->selectWherePaginated($where, $sortParam, 1, 50);
        return (($many) ? $result : $result[0]);
    }

    /**
     * Helper method for updating links to related records.
     */
    protected function updateRelationLinks($entity)
    {
    }

    /**
     * Helper method to process upload fields
     */
    protected function handleUploads($formData, $existingObject)
    {
        // initialise the upload handler
        $uploadManager = new MUTicket_UploadHandler();

        // list of upload fields to consider
        $uploadFields = array('images', 'files');
        // list of mandatory fields
        $uploadFieldsMandatory = array();

        $existingObjectData = $existingObject->toArray();

        // process all fields
        foreach ($uploadFields as $uploadField) {
            // check if an existing file must be deleted
            $hasOldFile = (!empty($existingObjectData[$uploadField]));
            $hasBeenDeleted = !$hasOldFile;
            if ($this->mode != 'create') {
                if (isset($formData[$uploadField . 'DeleteFile'])) {
                    if ($hasOldFile && $formData[$uploadField . 'DeleteFile'] === true) {
                        // remove upload file (and image thumbnails)
                        $existingObjectData = $uploadManager->deleteUploadFile($this->objectType, $existingObjectData, $uploadField);
                        if (empty($existingObjectData[$uploadField])) {
                            $existingObject[$uploadField] = '';
                        }
                    }
                    unset($formData[$uploadField . 'DeleteFile']);
                    $hasBeenDeleted = true;
                }
            }

            // look whether a file has been provided
            if (!$formData[$uploadField] || $formData[$uploadField]['size'] == 0) {
                // no file has been uploaded
                unset($formData[$uploadField]);
                // skip to next one
                continue;
            }

            if ($hasOldFile && $hasBeenDeleted !== true && $this->mode != 'create') {
                // remove old upload file (and image thumbnails)
                $existingObjectData = $uploadManager->deleteUploadFile($this->objectType, $existingObjectData, $uploadField);
                if (empty($existingObjectData[$uploadField])) {
                    $existingObject[$uploadField] = '';
                }
            }

            // do the actual upload (includes validation, physical file processing and reading meta data)
            $uploadResult = $uploadManager->performFileUpload($this->objectType, $formData, $uploadField);
            // assign the upload file name
            $formData[$uploadField] = $uploadResult['fileName'];
            // assign the meta data
            $formData[$uploadField . 'Meta'] = $uploadResult['metaData'];

            // if current field is mandatory check if everything has been done 
            if (in_array($uploadField, $uploadFieldsMandatory) && $formData[$uploadField] === false) {
                // mandatory upload has not been completed successfully
                return false;
            }

            // upload succeeded
        }
        return $formData;
    }
}
