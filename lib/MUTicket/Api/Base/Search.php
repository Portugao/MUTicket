<?php
/**
 * MUTicket.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUTicket
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.6.1 (http://modulestudio.de) at Sat Aug 31 12:39:20 CEST 2013.
 */

/**
 * Search api base class.
 */
class MUTicket_Api_Base_Search extends Zikula_AbstractApi
{
    /**
     * Get search plugin information.
     *
     * @return array The search plugin information
     */
    public function info()
    {
        return array('title'     => $this->name,
                     'functions' => array($this->name => 'search'));
    }
    
    /**
     * Display the search form.
     *
     * @param array $args List of arguments.
     *
     * @return string template output
     */
    public function options(array $args = array())
    {
        if (!SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            return '';
        }
    
        $view = Zikula_View::getInstance($this->name);
    
        $view->assign('active_ticket', (!isset($args['active_ticket']) || isset($args['active']['active_ticket'])));
        $view->assign('active_rating', (!isset($args['active_rating']) || isset($args['active']['active_rating'])));
        $view->assign('active_supporter', (!isset($args['active_supporter']) || isset($args['active']['active_supporter'])));
        $view->assign('active_currentState', (!isset($args['active_currentState']) || isset($args['active']['active_currentState'])));
        $view->assign('active_label', (!isset($args['active_label']) || isset($args['active']['active_label'])));
    
        return $view->fetch('search/options.tpl');
    }
    
    /**
     * Executes the actual search process.
     *
     * @param array $args List of arguments.
     *
     * @return boolean
     */
    public function search(array $args = array())
    {
        if (!SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            return '';
        }
    
        // ensure that database information of Search module is loaded
        ModUtil::dbInfoLoad('Search');
    
        // save session id as it is used when inserting search results below
        $sessionId  = session_id();
    
        // retrieve list of activated object types
        $searchTypes = isset($args['objectTypes']) ? (array)$args['objectTypes'] : (array) FormUtil::getPassedValue('search_muticket_types', array(), 'GETPOST');
    
        $controllerHelper = new MUTicket_Util_Controller($this->serviceManager);
        $utilArgs = array('api' => 'search', 'action' => 'search');
        $allowedTypes = $controllerHelper->getObjectTypes('api', $utilArgs);
        $entityManager = ServiceUtil::getService('doctrine.entitymanager');
        $currentPage = 1;
        $resultsPerPage = 50;
    
        foreach ($searchTypes as $objectType) {
            if (!in_array($objectType, $allowedTypes)) {
                continue;
            }
    
            $whereArray = array();
            $languageField = null;
            switch ($objectType) {
                case 'ticket':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.title';
                    $whereArray[] = 'tbl.text';
                    $whereArray[] = 'tbl.images';
                    $whereArray[] = 'tbl.files';
                    $whereArray[] = 'tbl.dueText';
                    $whereArray[] = 'tbl.currentState';
                    break;
                case 'rating':
                    $whereArray[] = 'tbl.workflowState';
                    break;
                case 'supporter':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.username';
                    $whereArray[] = 'tbl.supportcats';
                    break;
                case 'currentState':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.title';
                    $whereArray[] = 'tbl.description';
                    $whereArray[] = 'tbl.uploadIcon';
                    break;
                case 'label':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.name';
                    $whereArray[] = 'tbl.labelColor';
                    break;
            }
            $where = Search_Api_User::construct_where($args, $whereArray, $languageField);
    
            $entityClass = $this->name . '_Entity_' . ucwords($objectType);
            $repository = $entityManager->getRepository($entityClass);
    
            // get objects from database
            list($entities, $objectCount) = $repository->selectWherePaginated($where, '', $currentPage, $resultsPerPage, false);
    
            if ($objectCount == 0) {
                continue;
            }
    
            $idFields = ModUtil::apiFunc($this->name, 'selection', 'getIdFields', array('ot' => $objectType));
            $titleField = $repository->getTitleFieldName();
            $descriptionField = $repository->getDescriptionFieldName();
            foreach ($entities as $entity) {
                $urlArgs = array('ot' => $objectType);
                // create identifier for permission check
                $instanceId = '';
                foreach ($idFields as $idField) {
                    $urlArgs[$idField] = $entity[$idField];
                    if (!empty($instanceId)) {
                        $instanceId .= '_';
                    }
                    $instanceId .= $entity[$idField];
                }
                $urlArgs['id'] = $instanceId;
                /* commented out as it could exceed the maximum length of the 'extra' field
                if (isset($entity['slug'])) {
                    $urlArgs['slug'] = $entity['slug'];
                }*/
    
                if (!SecurityUtil::checkPermission($this->name . ':' . ucfirst($objectType) . ':', $instanceId . '::', ACCESS_OVERVIEW)) {
                    continue;
                }
    
                $title = ($titleField != '') ? $entity[$titleField] : $this->__('Item');
                $description = ($descriptionField != '') ? $entity[$descriptionField] : '';
                $created = (isset($entity['createdDate'])) ? $entity['createdDate']->format('Y-m-d H:i:s') : '';
    
                $searchItemData = array(
                    'title'   => $title,
                    'text'    => $description,
                    'extra'   => serialize($urlArgs),
                    'created' => $created,
                    'module'  => $this->name,
                    'session' => $sessionId
                );
    
                if (!DBUtil::insertObject($searchItemData, 'search_result')) {
                    return LogUtil::registerError($this->__('Error! Could not save the search results.'));
                }
            }
        }
    
        return true;
    }
    
    /**
     * Assign URL to items.
     *
     * @param array $args List of arguments.
     *
     * @return boolean
     */
    public function search_check(array $args = array())
    {
        $datarow = &$args['datarow'];
        $urlArgs = unserialize($datarow['extra']);
        $datarow['url'] = ModUtil::url($this->name, 'user', 'display', $urlArgs);
        return true;
    }
}
