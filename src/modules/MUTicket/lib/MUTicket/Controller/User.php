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
 * This is the User controller class providing navigation and interaction functionality.
 */
class MUTicket_Controller_User extends MUTicket_Controller_Base_User
{
    /**
     * This method provides a generic item list overview.
     *
     * @param string  $ot           Treated object type.
     * @param string  $sort         Sorting field.
     * @param string  $sortdir      Sorting direction.
     * @param int     $pos          Current pager position.
     * @param int     $num          Amount of entries to display.
     * @param string  $tpl          Name of alternative template (for alternative display options, feeds and xml output)
     * @param boolean $raw          Optional way to display a template instead of fetching it (needed for standalone output)
     * @return mixed Output.
     */
    public function view($args)
    {
// DEBUG: permission check aspect starts
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_READ));
// DEBUG: permission check aspect ends

        // parameter specifying which type of objects we are treating
        $objectType = $this->request->getGet()->filter('ot', 'ticket', FILTER_SANITIZE_STRING);
        $utilArgs = array('controller' => 'user', 'action' => 'view');
        if (!in_array($objectType, MUTicket_Util_Controller::getObjectTypes('controllerAction', $utilArgs))) {
            $objectType = MUTicket_Util_Controller::getDefaultObjectType('controllerAction', $utilArgs);
        }
        $entityClass = 'MUTicket_Entity_' . ucfirst($objectType);
        $repository = $this->entityManager->getRepository($entityClass);

        $tpl = $this->request->getGet()->filter('tpl', '', FILTER_SANITIZE_STRING);
        if ($tpl == 'tree') {
            $this->view->assign('trees', $repository->selectAllTrees());
            // fetch and return the appropriate template
            return MUTicket_Util_View::processTemplate($this->view, 'user', $objectType, 'view', $args);
        }

        // parameter for used sorting field
        $sort = $this->request->getGet()->filter('sort', '', FILTER_SANITIZE_STRING);
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
            $sort = $repository->getDefaultSortingField();
        }

        // parameter for used sort order
        $sdir = $this->request->getGet()->filter('sortdir', '', FILTER_SANITIZE_STRING);
        $sdir = strtolower($sdir);
        if ($sdir != 'asc' && $sdir != 'desc') {
            $sdir = 'asc';
        }

        // the current offset which is used to calculate the pagination
        $currentPage = (int) $this->request->getGet()->filter('pos', 1, FILTER_VALIDATE_INT);

        // the number of items displayed on a page for pagination
        $resultsPerPage = (int) $this->request->getGet()->filter('num', 0, FILTER_VALIDATE_INT);
        if ($resultsPerPage == 0) {
            $csv = (int) $this->request->getGet()->filter('usecsvext', 0, FILTER_VALIDATE_INT);
            $resultsPerPage = ($csv == 1) ? 999999 : $this->getVar('pagesize', 10);
        }

        // convenience vars to make code clearer
        if($entityClass == 'MUTicket_Entity_Ticket') {
        	$where = "tbl.parent_id is NULL";
        }
        else 
        {
        	$where = '';
        }
        $sortParam = $sort . ' ' . $sdir;

        // get objects from database
        list($objectData, $objectCount) = $repository->selectWherePaginated($where, $sortParam, $currentPage, $resultsPerPage);

        // build ModUrl instance for display hooks
        $currentUrlObject = new Zikula_ModUrl('MUTicket', 'user', 'view', ZLanguage::getLanguageCode(), array('ot' => $objectType));

        // assign the object data, sorting information and details for creating the pager
        $this->view->assign('items', $objectData)
                   ->assign('sort', $sort)
                   ->assign('sdir', $sdir)
                   ->assign('currentPage', $currentPage)
                   ->assign('pager', array('numitems'     => $objectCount,
                                           'itemsperpage' => $resultsPerPage))
                   ->assign('currentUrlObject', $currentUrlObject);

        // fetch and return the appropriate template
        return MUTicket_Util_View::processTemplate($this->view, 'user', $objectType, 'view', $args);
    }
    
    /**
     * This method provides a generic item detail view.
     *
     * @param string  $ot           Treated object type.
     * @param string  $tpl          Name of alternative template (for alternative display options, feeds and xml output)
     * @param boolean $raw          Optional way to display a template instead of fetching it (needed for standalone output)
     * @return mixed Output.
     */
    public function display($args)
    {
        // DEBUG: permission check aspect starts
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_READ));
        // DEBUG: permission check aspect ends

        // parameter specifying which type of objects we are treating
        $objectType = $this->request->getGet()->filter('ot', 'ticket', FILTER_SANITIZE_STRING);
        $utilArgs = array('controller' => 'user', 'action' => 'display');
        if (!in_array($objectType, MUTicket_Util_Controller::getObjectTypes('controllerAction', $utilArgs))) {
            $objectType = MUTicket_Util_Controller::getDefaultObjectType('controllerAction', $utilArgs);
        }
        $entityClass = 'MUTicket_Entity_' . ucfirst($objectType);
        $repository = $this->entityManager->getRepository($entityClass);

        $objectTemp = new $entityClass();
        $idFields = $objectTemp->get_idFields();
        $idValues = array();

        // retrieve identifier of the object we wish to view
        $idValues = MUTicket_Util_Controller::retrieveIdentifier($this->request, $args, $objectType, $idFields);
        $hasIdentifier = MUTicket_Util_Controller::isValidIdentifier($idValues);

        // check for unique permalinks (without id)
        $hasSlug = false;
        $slugTitle = '';
        if ($hasIdentifier === false) {
            $hasSlug = $objectTemp->get_hasUniqueSlug();
            if ($hasSlug) {
                $slugTitle = (isset($args['title']) && !empty($args['title'])) ? $args['title'] : $this->request->getGet()->filter('title', '', FILTER_SANITIZE_STRING);
                $hasSlug = (!empty($slugTitle));
            }
        }

        $this->throwNotFoundUnless(($hasIdentifier || $hasSlug), $this->__('Error! Invalid identifier received.'));

        // assign object data fetched from the database
        $objectData = null;
        if ($hasSlug) {
            $objectData = $repository->selectBySlug($slugTitle);
        } else {
            $objectData = $repository->selectById($idValues);
        }
        if ((!is_array($objectData) && !is_object($objectData)) || !isset($objectData[$idFields[0]])) {
            $this->throwNotFound($this->__('No such item.'));
        }

        // build ModUrl instance for display hooks
        $currentUrlArgs = array('ot' => $objectType);
        foreach ($idFields as $idField) {
            $currentUrlArgs[$idField] = $idValues[$idField];
        }
        $currentUrlObject = new Zikula_ModUrl('MUTicket', 'user', 'display', ZLanguage::getLanguageCode(), $currentUrlArgs);
        
        /*
         * Own code for handling the user for the rating
         */
        
        // get the supporterids
        
        $repositorySupporter = MUTicket_Util_View::getSupporterRepository();
        
        $supporters = $repositorySupporter->selectWhere();
        
        $supporterids = array();
        
        foreach ($supporters as $supporter) {
        	$supporterids[] = $supporter['id'];
        }
        
        // get actual userid
        $userid = UserUtil::getVar('uid');
        if (in_array($userid, $supporterids)) {
        	$kind = 1;
        }
        else {
        	$kind = 0;
        }

        // assign output data to view object.
        $this->view->assign($objectType, $objectData)
            ->assign('kind', $kind)
            ->assign('currentUrlObject', $currentUrlObject)
            ->assign($repository->getAdditionalTemplateParameters('controllerAction', $utilArgs));

        // fetch and return the appropriate template
        return MUTicket_Util_View::processTemplate($this->view, 'user', $objectType, 'display', $args);
    }
    
}
