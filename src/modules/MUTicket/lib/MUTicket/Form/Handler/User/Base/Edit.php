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
 * This handler class handles the page events of the Form called by the MUTicket_user_edit() function.
 * It collects common functionality required by different object types.
 *
 * Member variables in a form handler object are persisted across different page requests. This means
 * a member variable $this->X can be set on one request and on the next request it will still contain
 * the same value.
 *
 * A form handler will be notified of various events happening during it's life-cycle.
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
class MUTicket_Form_Handler_User_Base_Edit extends Zikula_Form_AbstractHandler
{
    /**
     * Persistent member vars
     */

    /**
     * @var string Name of treated object type.
     */
    protected $objectType;

    /**
     * @var string Name of treated object type starting with upper case.
     */
    protected $objectTypeCapital;

    /**
     * @var string Lower case version.
     */
    protected $objectTypeLower;

    /**
     * @var string Lower case name of multiple items (needed for hook areas).
     */
    protected $objectTypeLowerMultiple;

    /**
     * @var string Permission component based on object type.
     */
    protected $permissionComponent;

    /**
     * @var Zikula_EntityAccess Reference to treated entity instance.
     */
    protected $entityRef = false;

    /**
     * @var array List of identifier names.
     */
    protected $idFields = array();

    /**
     * @var array List of identifiers of treated entity.
     */
    protected $idValues = array();

    /**
     * @var mixed List of identifiers for incoming relationships.
     */
    protected $incomingIds = array();

    /**
     * @var string One of "create" or "edit".
     */
    protected $mode;

    /**
     * @var string Code defining the redirect goal after command handling.
     */
    protected $returnTo = null;

    /**
     * @var boolean Whether a create action is going to be repeated or not.
     */
    protected $repeatCreateAction = false;

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
     * @var boolean Whether an existing item is used as template for a new one
     */
    protected $hasTemplateId = false;

    /**
     * @var boolean Whether the PageLock extension is used for this entity type or not.
     */
    protected $hasPageLockSupport = false;

    /**
     * @var boolean Whether the entity is categorisable or not.
     */
    protected $hasCategories = false;

    /**
     * @var array Array with upload fields names and mandatory flags.
     */
    protected $uploadFields = array();



    /**
     * Post construction hook.
     *
     * @return mixed
     */
    public function setup()
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

        $this->permissionComponent = $this->name . ':' . $this->objectTypeCapital . ':';

        $entityClass = $this->name . '_Entity_' . ucfirst($this->objectType);
        $objectTemp = new $entityClass();
        $this->idFields = $objectTemp->get_idFields();

        // retrieve identifier of the object we wish to view
        $this->idValues = MUTicket_Util_Controller::retrieveIdentifier($this->request, array(), $this->objectType, $this->idFields);
        $hasIdentifier = MUTicket_Util_Controller::isValidIdentifier($this->idValues);

        $entity = null;
        $this->mode = ($hasIdentifier) ? 'edit' : 'create';

        if ($this->mode == 'edit') {
            if (!SecurityUtil::checkPermission($this->permissionComponent, '::', ACCESS_EDIT)) {
                // set an error message and return false
                return LogUtil::registerPermissionError();
            }

            $entity = $this->initEntityForEdit();

            if ($this->hasPageLockSupport === true && ModUtil::available('PageLock')) {
                // try to guarantee that only one person at a time can be editing this entity
                ModUtil::apiFunc('PageLock', 'user', 'pageLock',
                                         array('lockName' => $this->name . $this->objectTypeCapital . $this->createCompositeIdentifier(),
                                               'returnUrl' => $this->getRedirectUrl(null, $entity)));
            }
        }
        else {
            if (!SecurityUtil::checkPermission($this->permissionComponent, '::', ACCESS_ADD)) {
                return LogUtil::registerPermissionError();
            }

            $entity = $this->initEntityForCreation($entityClass);
        }

        $this->view->assign('mode', $this->mode)
                   ->assign('inlineUsage', $this->inlineUsage);

        if ($this->hasCategories === true) {
            $this->initCategoriesForEdit($entity);
        }

        // save entity reference for later reuse
        $this->entityRef = $entity;

        // everything okay, no initialization errors occured
        return true;
    }

    /**
     * Create concatenated identifier string (for composite keys).
     *
     * @return String concatenated identifiers. 
     */
    protected function createCompositeIdentifier()
    {
        $itemId = '';
        foreach ($this->idFields as $idField) {
            if (!empty($itemId)) {
                $itemId .= '_';
            }
            $itemId .= $this->idValues[$idField];
        }

        return $itemId;
    }

    /**
     * Decode a list of concatenated identifier strings (for composite keys).
     * This method is used for reading selected relationships.
     *
     * @param Array $itemIds List of concatenated identifiers.
     * @param Array $idFields List of identifier names.
     * @return Array with list of single identifiers. 
     */
    protected function decodeCompositeIdentifier($itemIds, $idFields)
    {
        $idValues = array();
        foreach ($idFields as $idField) {
            $idValues[$idField] = array();
        }
        foreach ($itemIds as $itemId) {
            $itemIdParts = explode('_', $itemId);
            $i = 0;
            foreach ($idFields as $idField) {
                $idValues[$idField][] = $itemIdParts[$i];
                $i++;
            }
        }
        return $idValues;
    }

    /**
     * Enrich a given args array for easy creation of display urls with composite keys.
     *
     * @param Array $args List of arguments to be extended.
     * @return Array enriched arguments list. 
     */
    protected function addIdentifiersToUrlArgs($args = array())
    {
        foreach ($this->idFields as $idField) {
            $args[$idField] = $this->idValues[$idField];
        }

        return $args;
    }

    /**
     * Initialise existing entity for editing.
     *
     * @return Zikula_EntityAccess desired entity instance or null 
     */
    protected function initEntityForEdit()
    {
        $entity = ModUtil::apiFunc($this->name, 'selection', 'getEntity', array('ot' => $this->objectType, 'id' => $this->idValues));
        if ($entity == null) {
            return LogUtil::registerError($this->__('No such item.'));
        }
        return $entity;
    }

    /**
     * Initialise new entity for creation.
     *
     * @return Zikula_EntityAccess desired entity instance or null 
     */
    protected function initEntityForCreation($entityClass)
    {
        $this->hasTemplateId = false;
        $templateId = $this->request->getGet()->get('astemplate', '');
        if (!empty($templateId)) {
            $templateIdValueParts = explode('_', $templateId);
            $this->hasTemplateId = (count($templateIdValueParts) == count($this->idFields));
        }

        if ($this->hasTemplateId === true) {
            $templateIdValues = array();
            $i = 0;
            foreach ($this->idFields as $idField) {
                $templateIdValues[$idField] = $templateIdValueParts[$i];
                $i++;
            }
            // reuse existing entity
            $entity = ModUtil::apiFunc($this->name, 'selection', 'getEntity', array('ot' => $this->objectType, 'id' => $templateIdValues));
            if ($entity == null) {
                return LogUtil::registerError($this->__('No such item.'));
            }
            foreach ($this->idFields as $idField) {
                $entity[$idField] = null;
            }
        }
        else {
            $entityClass = $this->name . '_Entity_' . ucfirst($this->objectType);
            $entity = new $entityClass();
        }

        return $entity;
    }

    /**
     * Initialise categories.
     *
     * @param Zikula_EntityAccess $entity treated entity instance.
     */
    protected function initCategoriesForEdit($entity)
    {
        // assign the actual object for categories listener
        $this->view->assign($this->objectTypeLower . 'Obj', $entity);

        // load and assign registered categories
        $categories = CategoryRegistryUtil::getRegisteredModuleCategories($this->name, $this->objectTypeCapital, $this->idFields[0]);
        $this->view->assign('registries', $categories);
    }

    /**
     * Method stub for own additions in subclasses.
     *
     * @depreciated to be removed in favour of postInitialize().
     */
    protected function initializeAdditions()
    {
    }

    /**
     * Post-initialise hook.
     *
     * @return void
     */
    public function postInitialize()
    {
        $entityClass = $this->name . '_Entity_' . ucfirst($this->objectType);
        $repository = $this->entityManager->getRepository($entityClass);
        $utilArgs = array('controller' => 'user', 'action' => 'edit', 'mode' => $this->mode);
        $this->view->assign($repository->getAdditionalTemplateParameters('controllerAction', $utilArgs));
    }

    /**
     * Select a related object in create mode.
     *
     * @return array Single result or list of results.
     */
    protected function retrieveRelatedObjects($objectType, $relationInputFieldName, $many = false, $source = 'GET')
    {
        $repository = $this->entityManager->getRepository($this->name . '_Entity_' . ucfirst($objectType));

        $idFields = ModUtil::apiFunc($this->name, 'selection', 'getIdFields', array('ot' => $objectType));

        $where = '';

        $inputValue = '';
        if ($source == 'POST') {
            $inputValue = $this->request->getPost()->get($relationInputFieldName, '');
        } else {
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
                $where .= 'tbl.' . $idField . ' = ' . DataUtil::formatForStore($inputValueParts[$i]);
            }
            $i++;
        }
        $selectionArgs = array(
            'ot' => $objectType,
            'where' => $where,
            'orderBy' => $repository->getDefaultSortingField() . ' asc',
            'currentPage' => 1,
            'resultsPerPage' => 50
        );
        list($entities, $objectCount) = ModUtil::apiFunc($this->name, 'selection', 'getEntitiesPaginated', $selectionArgs);
        return (($many) ? $entities : $entities[0]);
    }
    /**
     * Get list of allowed redirect codes.
     */
    protected function getRedirectCodes()
    {
        $codes = array();
        // main page of admin area
        $codes[] = 'admin';
        // admin list of entities
        $codes[] = 'adminView';
        // admin display page of treated entity
        $codes[] = 'adminDisplay';
        // main page of user area
        $codes[] = 'user';
        // user list of entities
        $codes[] = 'userView';
        // user display page of treated entity
        $codes[] = 'userDisplay';
        // main page of ajax area
        $codes[] = 'ajax';
        // main page of account area
        $codes[] = 'account';
        return $codes;
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
    public function HandleCommand(Zikula_Form_View $view, &$args)
    {
        if ($args['commandName'] == 'delete') {
            if (!SecurityUtil::checkPermission($this->permissionComponent, '::', ACCESS_DELETE)) {
                return LogUtil::registerPermissionError();
            }
        }

        if (!in_array($args['commandName'], array('delete', 'cancel'))) {
            // do forms validation including checking all validators on the page to validate their input
            if (!$this->view->isValid()) {
                return false;
            }
        }

        $entityClass = $this->name . '_Entity_' . ucfirst($this->objectType);
        $repository = $this->entityManager->getRepository($entityClass);
        if ($this->hasTranslatableFields === true) {
            $transRepository = $this->entityManager->getRepository($entityClass . 'Translation');
        }

        $result = $this->fetchInputData($view, $args);
        if ($result === false) {
             return $result;
        }

        $hookAreaPrefix = 'muticket.ui_hooks.' . $this->objectTypeLowerMultiple;

        // get treated entity reference from persisted member var
        $entity = $this->entityRef;

        if (in_array($args['commandName'], array('create', 'update'))) {
            // event handling if user clicks on create or update

            // Let any hooks perform additional validation actions
            $hook = new Zikula_ValidationHook($hookAreaPrefix . '.validate_edit', new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            if ($validators->hasErrors()) {
                return false;
            }

            $this->performUpdate($args);

            $success = true;
            if ($args['commandName'] == 'create') {
                // store new identifier
                foreach ($this->idFields as $idField) {
                    $this->idValues[$idField] = $entity[$idField];
                    // check if the insert has worked, might become obsolete due to exception usage
                    if (!$this->idValues[$idField]) {
                        $success = false;
                        break;
                    }
                }
            } else if ($args['commandName'] == 'update') {
            }
            $this->addDefaultMessage($args, $success);

            // Let any hooks know that we have created or updated an item
            $urlArgs = array('ot' => $this->objectType);
            $urlArgs = $this->addIdentifiersToUrlArgs($urlArgs);
            $url = new Zikula_ModUrl($this->name, 'user', 'display', ZLanguage::getLanguageCode(), $urlArgs);
            $hook = new Zikula_ProcessHook($hookAreaPrefix . '.process_edit', $this->createCompositeIdentifier(), $url);
            $this->notifyHooks($hook);
        } else if ($args['commandName'] == 'delete') {
            // event handling if user clicks on delete

            // Let any hooks perform additional validation actions
            $hook = new Zikula_ValidationHook($hookAreaPrefix . '.validate_delete', new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            if ($validators->hasErrors()) {
                return false;
            }

            // delete entity
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            $this->addDefaultMessage($args, true);

            // Let any hooks know that we have deleted an item
            $hook = new Zikula_ProcessHook($hookAreaPrefix . '.process_delete', $this->createCompositeIdentifier());
            $this->notifyHooks($hook);
        } else if ($args['commandName'] == 'cancel') {
            // event handling if user clicks on cancel
        }

        if ($args['commandName'] != 'cancel') {
            // clear view cache to reflect our changes
            $this->view->clear_cache();
        }

        if ($this->hasPageLockSupport === true && $this->mode == 'edit') {
            ModUtil::apiFunc('PageLock', 'user', 'releaseLock',
                             array('lockName' => $this->name . $this->objectTypeCapital . $this->createCompositeIdentifier()));
        }

        return $this->view->redirect($this->getRedirectUrl($args, $entity));
    }

    /**
     * Prepare update of categories.
     *
     * @param Zikula_EntityAccess $entity     currently treated entity instance.
     * @param Array               $entityData form data to be merged.
     */
    protected function processCategoriesForUpdate($entity, $entityData)
    {
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
        $message = '';
        switch ($args['commandName']) {
            case 'create':
                    if ($success === true) {
                        $message = $this->__('Done! Item created.');
                    } else {
                        $message = $this->__('Error! Creation attempt failed.');
                    }
                    break;
            case 'update':
                    if ($success === true) {
                        $message = $this->__('Done! Item updated.');
                    } else {
                        $message = $this->__('Error! Update attempt failed.');
                    }
                    break;
            case 'update':
                    if ($success === true) {
                        $message = $this->__('Done! Item deleted.');
                    } else {
                        $message = $this->__('Error! Deletion attempt failed.');
                    }
                    break;
        }
        return $message;
    }

    /**
     * Add success or error message to session.
     *
     * @param Array   $args    arguments from handleCommand method.
     * @param Boolean $success true if this is a success, false for default error.
     */
    protected function addDefaultMessage($args, $success = false)
    {
        $message = $this->getDefaultMessage($args, $success);
        if (!empty($message)) {
            if ($success === true) {
                LogUtil::registerStatus($message);
            } else {
                LogUtil::registerError($message);
            }
        }
    }

    /**
     * Input data processing called by handleCommand method.
     */
    public function fetchInputData(Zikula_Form_View $view, &$args)
    {
        // fetch posted data input values as an associative array
        $formData = $this->view->getValues();
        // we want the array with our field values
        $entityData = $formData[$this->objectTypeLower];
        unset($formData[$this->objectTypeLower]);

        // get treated entity reference from persisted member var
        $entity = $this->entityRef;


        if (in_array($args['commandName'], array('create', 'update', 'delete'))) {
            if (count($this->uploadFields) > 0) {
                $entityData = $this->handleUploads($entityData, $entity);
                if ($entityData == false) {
                    return false;
                }
            }


        }

        if (isset($entityData['repeatcreation'])) {
            if ($args['commandName'] == 'create') {
                $this->repeatCreateAction = $entityData['repeatcreation'];
            }
            unset($entityData['repeatcreation']);
        }

        // assign fetched data
        $entity->merge($entityData);

        // save updated entity
        $this->entityRef = $entity;

        // return remaining form data
        return $formData;
    }
    /**
     * Executing insert and update statements
     *
     * @param Array   $args    arguments from handleCommand method.
     */
    public function performUpdate($args)
    {
        // stub for subclasses
    }

    /**
     * Reassign options chosen by the user to avoid unwanted form state resets.
     * Necessary until issue #23 is solved.
     */
    public function reassignRelatedObjects()
    {
        // stub for subclasses
    }

    /**
     * Helper method to process upload fields
     */
    protected function handleUploads($formData, $existingObject)
    {
        if (!count($this->uploadFields)) {
            return $formData;
        }

        // initialise the upload handler
        $uploadManager = new MUTicket_UploadHandler();
        $existingObjectData = $existingObject->toArray();

        // process all fields
        foreach ($this->uploadFields as $uploadField => $isMandatory) {
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
            if ($isMandatory && $formData[$uploadField] === false) {
                // mandatory upload has not been completed successfully
                return false;
            }

            // upload succeeded
        }

        return $formData;
    }
}
