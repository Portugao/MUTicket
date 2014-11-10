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
 * This handler class handles the page events of the Form called by the MUTicket_user_edit() function.
 * It aims on the ticket object type.
 */
class MUTicket_Form_Handler_User_Ticket_Edit extends MUTicket_Form_Handler_User_Ticket_Base_Edit
{
    /**
     * Initialize form handler.
     *
     * This method takes care of all necessary initialisation of our data and form states.
     *
     * @return boolean False in case of initialization errors, otherwise true.
     */
    public function initialize(Zikula_Form_View $view)
    {
        // we rule the text for the button to create tickets or answers
        $func = $this->request->query->filter('func', 'main', FILTER_SANITIZE_STRING);

        // We check for supportes who are active
        // If there is no supporter active, we break the input process
        $supporteractive = MUTicket_Util_View::checkIfSupporters();
        if ($supporteractive == 0) {
            $url = ModUtil::url($this->name, 'user', 'view', array('ot' => 'ticket', 'state' => 1));
            return LogUtil::registerError(__('Sorry. Our support is not available at the moment!'),0 , $url);
        }
         
        parent::initialize($view);

        $id = $this->request->query->filter('id', 0, FILTER_SANITIZE_NUMBER_INT);
        if ($id > 0) {
            $repository = MUTicket_Util_Model::getTicketRepository();
            $entity = $repository->selectById($id);
            $this->view->assign('owner', $entity['owner']);
        }

        $fileSize = MUTicket_Util_Controller::maxSize();

        if (($id == 0 && $func == 'edit') || ($id > 0 && $func == 'display')) {
            // set mode to create
            $this->mode = 'create';
        }

        // we assign to template
        $this->view->assign('mode', $this->mode)
        ->assign('func', $func)
        ->assign('ticketid', $id)
        ->assign('fileSize', $fileSize);

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
        // we check if the actual user may create new tickets
        // we check if the setting for supporter group is enabled
        // if not we look if the user is a supporter or not
        $supporterTickets = MUTicket_Util_View::userForRating(2);

        // if user may not create tickets
        if ($supporterTickets == 0 && $func == 'edit') {
            $url = ModUtil::url($this->name, 'user', 'view');
            return LogUtil::registerPermissionError($url);
        }
        parent::postInitialize();
    }

    /**
     * Command event handler.
     *
     * This event handler is called when a command is issued by the user.
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
        // We check if the user create a new parent ticket or edit an existing parent ticket
        $id = $this->request->request->filter('ticketid', 0, FILTER_SANITIZE_NUMBER_INT);
        // We get parentid
        // We check if ticket is a parent ticket
        $parentid = $this->request->request->filter('muticketTicket_ParentItemList' , 0, FILTER_SANITIZE_NUMBER_INT);

        // we get a service manager
        $serviceManager = ServiceUtil::getManager();
        // we get a model helper
        $modelHelper = new MUTicket_Util_Model($serviceManager);
        // we get a view helper
        $viewHelper = new MUTicket_Util_View($serviceManager);
        // we get a setting helper
        $settingHelper = new MUTicket_Util_Base_Settings($serviceManager);
        // if user may not create tickets
        if (ModUtil::getVar($this->name, 'supporterTickets') == 0 && $viewHelper->userForRating(2) == 0 && $parentid == 0 && $id == 0) {
            $url = ModUtil::url($this->name, 'user', 'view');
            return LogUtil::registerPermissionError($url);
        }

        $result = parent::handleCommand($view, $args);

        if ($result === false) {
            return $result;
        }
        else {
             
            // fetch posted data input values as an associative array
            $formData = $this->view->getValues();
            // we want the array with our field values
            //$entity = $formData[$this->objectTypeLower];
            $repository = $modelHelper->getTicketRepository();
            $entity = $repository->selectById($this->idValues['id']);

            // Get relevant datas for mailing
            $data['id'] = $this->idValues['id'];
            $data['new'] = $id;
            $data['parentid'] = $parentid;
            $data['title'] = $entity['title'];
            $data['text'] = $entity['text'];
            $data['categories'] = $entity['categories'];

            $message = $settingHelper->handleModvarsPostPersist($data);
        }
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

        // we check if the entry is a parent ticket
        $parentid = $this->request->getPost()->filter('muticketTicket_ParentItemList' , null, FILTER_SANITIZE_STRING);
         
        $message = '';
        switch ($args['commandName']) {
            case 'submit':
                $message = $this->__('Done! Ticket created.');
                if ($parentid > 0) {
                    $message = $this->__('Done! Answer created.');
                }
                break;
            case 'update':
                $message = $this->__('Done! Ticket updated.');
                break;
            case 'delete':
                $message = $this->__('Done! Ticket deleted.');
                break;
        }
        return $message;
    }

    /**
     * Get the default redirect url. Required if no returnTo parameter has been supplied.
     * This method is called in handleCommand so we know which command has been performed.
     */
    protected function getDefaultReturnUrl($args)
    {
        // we get parentid
        // We check if ticket is a parent ticket
        $parentid = $this->request->request->filter('muticketTicket_ParentItemList' , 0, FILTER_SANITIZE_NUMBER_INT);

        $id = $this->request->query->filter('id' , 0, FILTER_SANITIZE_NUMBER_INT);
        
        
        // redirect to the list of tickets
        $viewArgs = array('ot' => $this->objectType);
        $url = ModUtil::url($this->name, 'user', 'view', $viewArgs);

        if ($this->mode == 'create' && $parentid == 0) {
            // redirect to just created parent ticket
            $url = ModUtil::url($this->name, 'user', 'display', array('ot' => 'ticket', 'id' => $this->idValues['id']));
        } elseif ($this->mode == 'create' && $parentid > 0) {
            // redirect to parent ticket
            $url = ModUtil::url($this->name, 'user', 'display', array('ot' => 'ticket', 'id' => $parentid));
        } elseif ($this->mode == 'edit') {            
            $url = ModUtil::url($this->name, 'user', 'display', array('ot' => 'ticket', 'id' => $id));
          }

        return $url;
    }
}
