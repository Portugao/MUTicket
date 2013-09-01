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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

use DoctrineExtensions\Paginate\Paginate;

/**
 * Repository class used to implement own convenience methods for performing certain DQL queries.
 *
 * This is the base repository class for current state entities.
 */
class MUTicket_Entity_Repository_Base_CurrentState extends EntityRepository
{
    /**
     * @var string The default sorting field/expression.
     */
    protected $defaultSortingField = 'title';

    /**
     * @var array Additional arguments given by the calling controller.
     */
    protected $controllerArguments = array();

    /**
     * Retrieves an array with all fields which can be used for sorting instances.
     *
     * @return array
     */
    public function getAllowedSortingFields()
    {
        return array(
            'id',
            'workflowState',
            'title',
            'description',
            'uploadIcon',
            'createdUserId',
            'updatedUserId',
            'createdDate',
            'updatedDate',
        );
    }

    /**
     * Get default sorting field.
     *
     * @return string
     */
    public function getDefaultSortingField()
    {
        return $this->defaultSortingField;
    }
    
    /**
     * Set default sorting field.
     *
     * @param string $defaultSortingField.
     *
     * @return void
     */
    public function setDefaultSortingField($defaultSortingField)
    {
        $this->defaultSortingField = $defaultSortingField;
    }
    
    /**
     * Get controller arguments.
     *
     * @return array
     */
    public function getControllerArguments()
    {
        return $this->controllerArguments;
    }
    
    /**
     * Set controller arguments.
     *
     * @param array $controllerArguments.
     *
     * @return void
     */
    public function setControllerArguments(array $controllerArguments = Array())
    {
        $this->controllerArguments = $controllerArguments;
    }
    

    /**
     * Returns name of the field used as title / name for entities of this repository.
     *
     * @return string Name of field to be used as title.
     */
    public function getTitleFieldName()
    {
        $fieldName = 'title';

        return $fieldName;
    }

    /**
     * Returns name of the field used for describing entities of this repository.
     *
     * @return string Name of field to be used as description.
     */
    public function getDescriptionFieldName()
   {
        $fieldName = 'description';

        return $fieldName;
    }

    /**
     * Returns name of first upload field which is capable for handling images.
     *
     * @return string Name of field to be used for preview images.
     */
    public function getPreviewFieldName()
    {
        $fieldName = 'uploadIcon';

        return $fieldName;
    }

    /**
     * Returns name of the date(time) field to be used for representing the start
     * of this object. Used for providing meta data to the tag module.
     *
     * @return string Name of field to be used as date.
     */
    public function getStartDateFieldName()
    {
        $fieldName = 'createdDate';

        return $fieldName;
    }

    /**
     * Returns an array of additional template variables which are specific to the object type treated by this repository.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType).
     * @param array  $args    Additional arguments.
     *
     * @return array List of template variables to be assigned.
     */
    public function getAdditionalTemplateParameters($context = '', $args = array())
    {
        if (!in_array($context, array('controllerAction', 'api', 'actionHandler', 'block', 'contentType'))) {
            $context = 'controllerAction';
        }
    
        $templateParameters = array();
    
        if ($context == 'controllerAction') {
            if (!isset($args['action'])) {
                $args['action'] = FormUtil::getPassedValue('func', 'main', 'GETPOST');
            }
            if (in_array($args['action'], array('main', 'view'))) {
                $templateParameters = $this->getViewQuickNavParameters($context, $args);
                $listHelper = new MUTicket_Util_ListEntries(ServiceUtil::getManager());
                $templateParameters['workflowStateItems'] = $listHelper->getEntries('currentState', 'workflowState');
            }
    
            // initialise Imagine preset instances
            $imageHelper = new MUTicket_Util_Image(ServiceUtil::getManager());
    
            $objectType = 'currentState';
            $templateParameters[$objectType . 'ThumbPresetUploadIcon'] = $imageHelper->getPreset($objectType, 'uploadIcon', $context, $args);
            if (in_array($args['action'], array('display', 'view'))) {
                // use separate preset for images in related items
                $templateParameters['relationThumbPreset'] = $imageHelper->getCustomPreset('', '', 'MUTicket_relateditem', $context, $args);
            }
        }
    
        // in the concrete child class you could do something like
        // $parameters = parent::getAdditionalTemplateParameters($context, $args);
        // $parameters['myvar'] = 'myvalue';
        // return $parameters;
    
        return $templateParameters;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType).
     * @param array  $args    Additional arguments.
     *
     * @return array List of template variables to be assigned.
     */
    protected function getViewQuickNavParameters($context = '', $args = array())
    {
        if (!in_array($context, array('controllerAction', 'api', 'actionHandler', 'block', 'contentType'))) {
            $context = 'controllerAction';
        }
    
        $parameters = array();
        $parameters['workflowState'] = isset($this->controllerArguments['workflowState']) ? $this->controllerArguments['workflowState'] : FormUtil::getPassedValue('workflowState', '', 'GET');
        $parameters['searchterm'] = isset($this->controllerArguments['searchterm']) ? $this->controllerArguments['searchterm'] : FormUtil::getPassedValue('searchterm', '', 'GET');
        
    
        // in the concrete child class you could do something like
        // $parameters = parent::getViewQuickNavParameters($context, $args);
        // $parameters['myvar'] = 'myvalue';
        // return $parameters;
    
        return $parameters;
    }

    /**
     * Helper method for truncating the table.
     * Used during installation when inserting default data.
     *
     * @return void
     */
    public function truncateTable()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->delete('MUTicket_Entity_CurrentState', 'tbl');
        $query = $qb->getQuery();
        $query->execute();
    }

    /**
     * Deletes all objects created by a certain user.
     *
     * @param integer $userId The userid of the creator to be removed.
     *
     * @return void
     */
    public function deleteCreator($userId)
    {
        // check id parameter
        if ($userId == 0 || !is_numeric($userId)) {
            return LogUtil::registerArgsError();
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->delete('MUTicket_Entity_CurrentState', 'tbl')
           ->where('tbl.createdUserId = :creator')
           ->setParameter('creator', DataUtil::formatForStore($userId));
        $query = $qb->getQuery();
        $query->execute();
    }
    
    /**
     * Deletes all objects updated by a certain user.
     *
     * @param integer $userId The userid of the last editor to be removed.
     *
     * @return void
     */
    public function deleteLastEditor($userId)
    {
        // check id parameter
        if ($userId == 0 || !is_numeric($userId)) {
            return LogUtil::registerArgsError();
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->delete('MUTicket_Entity_CurrentState', 'tbl')
           ->where('tbl.updatedUserId = :editor')
           ->setParameter('editor', DataUtil::formatForStore($userId));
        $query = $qb->getQuery();
        $query->execute();
    }
    
    /**
     * Updates the creator of all objects created by a certain user.
     *
     * @param integer $userId    The userid of the creator to be replaced.
     * @param integer $newUserId The new userid of the creator as replacement.
     *
     * @return void
     */
    public function updateCreator($userId, $newUserId)
    {
        // check id parameter
        if ($userId == 0 || !is_numeric($userId)
         || $newUserId == 0 || !is_numeric($newUserId)) {
            return LogUtil::registerArgsError();
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->update('MUTicket_Entity_CurrentState', 'tbl')
           ->set('tbl.createdUserId', $newUserId)
           ->where('tbl.createdUserId = :creator')
           ->setParameter('creator', DataUtil::formatForStore($userId));
        $query = $qb->getQuery();
        $query->execute();
    }
    
    /**
     * Updates the last editor of all objects updated by a certain user.
     *
     * @param integer $userId    The userid of the last editor to be replaced.
     * @param integer $newUserId The new userid of the last editor as replacement.
     *
     * @return void
     */
    public function updateLastEditor($userId, $newUserId)
    {
        // check id parameter
        if ($userId == 0 || !is_numeric($userId)
         || $newUserId == 0 || !is_numeric($newUserId)) {
            return LogUtil::registerArgsError();
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->update('MUTicket_Entity_CurrentState', 'tbl')
           ->set('tbl.updatedUserId', $newUserId)
           ->where('tbl.updatedUserId = :editor')
           ->setParameter('editor', DataUtil::formatForStore($userId));
        $query = $qb->getQuery();
        $query->execute();
    }

    /**
     * Adds id filters to given query instance.
     *
     * @param mixed                     $id The id (or array of ids) to use to retrieve the object.
     * @param Doctrine\ORM\QueryBuilder $qb Query builder to be enhanced.
     *
     * @return Doctrine\ORM\QueryBuilder Enriched query builder instance.
     */
    protected function addIdFilter($id, QueryBuilder $qb)
    {
        if (is_array($id)) {
            foreach ($id as $fieldName => $fieldValue) {
                $qb->andWhere('tbl.' . $fieldName . ' = :' . $fieldName)
                   ->setParameter($fieldName, DataUtil::formatForStore($fieldValue));
            }
        } else {
            $qb->andWhere('tbl.id = :id')
               ->setParameter('id', DataUtil::formatForStore($id));
        }
        return $qb;
    }
    
    /**
     * Selects an object from the database.
     *
     * @param mixed   $id       The id (or array of ids) to use to retrieve the object (optional) (default=0).
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true).
     * @param boolean $slimMode If activated only some basic fields are selected without using any joins (optional) (default=false).
     *
     * @return array|MUTicket_Entity_CurrentState retrieved data array or MUTicket_Entity_CurrentState instance
     */
    public function selectById($id = 0, $useJoins = true, $slimMode = false)
    {
        // check id parameter
        if ($id == 0) {
            return LogUtil::registerArgsError();
        }
    
        $qb = $this->_intBaseQuery('', '', $useJoins, $slimMode);
    
        $qb = $this->addIdFilter($id, $qb);
    
        $query = $this->getQueryFromBuilder($qb);
    
        $results = $query->getResult();//OneOrNullResult();
        return (count($results) > 0) ? $results[0] : null;
    }

    /**
     * Adds where clauses excluding desired identifiers from selection.
     *
     * @param Doctrine\ORM\QueryBuilder $qb        Query builder to be enhanced.
     * @param integer                   $excludeId The id (or array of ids) to be excluded from selection.
     *
     * @return Doctrine\ORM\QueryBuilder Enriched query builder instance.
     */
    protected function addExclusion(QueryBuilder $qb, $excludeId)
    {
        if ($excludeId > 0) {
            $qb->andWhere('tbl.id != :excludeId')
               ->setParameter('excludeId', DataUtil::formatForStore($excludeId));
        }
        return $qb;
    }

    /**
     * Selects a list of objects with a given where clause.
     *
     * @param string  $where    The where clause to use when retrieving the collection (optional) (default='').
     * @param string  $orderBy  The order-by clause to use when retrieving the collection (optional) (default='').
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true).
     * @param boolean $slimMode If activated only some basic fields are selected without using any joins (optional) (default=false).
     *
     * @return ArrayCollection collection containing retrieved MUTicket_Entity_CurrentState instances
     */
    public function selectWhere($where = '', $orderBy = '', $useJoins = true, $slimMode = false)
    {
        $qb = $this->_intBaseQuery($where, $orderBy, $useJoins, $slimMode);
        if (!$useJoins || !$slimMode) {
            $qb = $this->addCommonViewFilters($qb);
        }
    
        $query = $this->getQueryFromBuilder($qb);
    
        return $query->getResult();
    }

    /**
     * Returns query builder instance for retrieving a list of objects with a given where clause and pagination parameters.
     *
     * @param Doctrine\ORM\QueryBuilder $qb             Query builder to be enhanced.
     * @param integer                   $currentPage    Where to start selection
     * @param integer                   $resultsPerPage Amount of items to select
     *
     * @return array Created query instance and amount of affected items.
     */
    protected function getSelectWherePaginatedQuery(QueryBuilder $qb, $currentPage = 1, $resultsPerPage = 25)
    {
        $qb = $this->addCommonViewFilters($qb);
    
        $query = $this->getQueryFromBuilder($qb);
        $offset = ($currentPage-1) * $resultsPerPage;
    
        // count the total number of affected items
        $count = Paginate::getTotalQueryResults($query);
    
        $query->setFirstResult($offset)
              ->setMaxResults($resultsPerPage);
    
        return array($query, $count);
    }
    
    /**
     * Selects a list of objects with a given where clause and pagination parameters.
     *
     * @param string  $where          The where clause to use when retrieving the collection (optional) (default='').
     * @param string  $orderBy        The order-by clause to use when retrieving the collection (optional) (default='').
     * @param integer $currentPage    Where to start selection
     * @param integer $resultsPerPage Amount of items to select
     * @param boolean $useJoins       Whether to include joining related objects (optional) (default=true).
     *
     * @return Array with retrieved collection and amount of total records affected by this query.
     */
    public function selectWherePaginated($where = '', $orderBy = '', $currentPage = 1, $resultsPerPage = 25, $useJoins = true)
    {
        $qb = $this->_intBaseQuery($where, $orderBy, $useJoins);
        list($query, $count) = $this->getSelectWherePaginatedQuery($qb, $currentPage, $resultsPerPage);
    
        $result = $query->getResult();
    
        return array($result, $count);
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     *
     * @param Doctrine\ORM\QueryBuilder $qb Query builder to be enhanced.
     *
     * @return Doctrine\ORM\QueryBuilder Enriched query builder instance.
     */
    protected function addCommonViewFilters(QueryBuilder $qb)
    {
        /* commented out to allow default filters also for other calls, like content types and mailz
        $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST');
        if (!in_array($currentFunc, array('main', 'view', 'finder'))) {
            return $qb;
        }*/
    
        $parameters = $this->getViewQuickNavParameters('', array());
        foreach ($parameters as $k => $v) {
            if ($k == 'catId') {
                // single category filter
                if ($v > 0) {
                    $qb->andWhere('tblCategories.category = :category')
                       ->setParameter('category', DataUtil::formatForStore($v));
                }
            } elseif ($k == 'catIdList') {
                // multi category filter
                /* old
                $qb->andWhereIn('tblCategories.category IN (:categories)')
                   ->setParameter('categories', DataUtil::formatForStore($v));
                 */
                $categoryFiltersPerRegistry = ModUtil::apiFunc('MUTicket', 'category', 'buildFilterClauses', array('ot' => 'currentState', 'catids' => $v));
                if (count($categoryFiltersPerRegistry) > 0) {
                    $qb->andWhere('(' . implode(' OR ', $categoryFiltersPerRegistry) . ')');
                }
            } elseif ($k == 'searchterm') {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter($qb, $v);
                }
            } else {
                // field filter
                if ($v != '' || (is_numeric($v) && $v > 0)) {
                    if ($k == 'workflowState' && substr($v, 0, 1) == '!') {
                        $qb->andWhere('tbl.' . $k . ' != :' . $k)
                           ->setParameter($k, DataUtil::formatForStore(substr($v, 1, strlen($v)-1)));
                    } elseif (substr($v, 0, 1) == '%') {
                        $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                           ->setParameter($k, '%' . DataUtil::formatForStore($v) . '%');
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, DataUtil::formatForStore($v));
                   }
                }
            }
        }
    
        $qb = $this->applyDefaultFilters($qb, $parameters);
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     *
     * @param Doctrine\ORM\QueryBuilder $qb         Query builder to be enhanced.
     * @param array                     $parameters List of determined filter options.
     *
     * @return Doctrine\ORM\QueryBuilder Enriched query builder instance.
     */
    protected function applyDefaultFilters(QueryBuilder $qb, $parameters)
    {
        $currentModule = ModUtil::getName();//FormUtil::getPassedValue('module', '', 'GETPOST');
        $currentType = FormUtil::getPassedValue('type', 'user', 'GETPOST');
        if ($currentType == 'admin' && $currentModule == 'MUTicket') {
            return $qb;
        }
    
        if (!in_array('workflowState', array_keys($parameters)) || empty($parameters['workflowState'])) {
            // per default we show approved current states only
            $onlineStates = array('approved');
            $qb->andWhere('tbl.workflowState IN (:onlineStates)')
               ->setParameter('onlineStates', DataUtil::formatForStore($onlineStates));
        }
    
        return $qb;
    }

    /**
     * Selects entities by a given search fragment.
     *
     * @param string  $fragment       The fragment to search for.
     * @param array   $exclude        Comma separated list with ids to be excluded from search.
     * @param string  $orderBy        The order-by clause to use when retrieving the collection (optional) (default='').
     * @param integer $currentPage    Where to start selection
     * @param integer $resultsPerPage Amount of items to select
     * @param boolean $useJoins       Whether to include joining related objects (optional) (default=true).
     *
     * @return Array with retrieved collection and amount of total records affected by this query.
     */
    public function selectSearch($fragment = '', $exclude = array(), $orderBy = '', $currentPage = 1, $resultsPerPage = 25, $useJoins = true)
    {
        $qb = $this->_intBaseQuery('', $orderBy, $useJoins);
        if (count($exclude) > 0) {
            $exclude = implode(', ', $exclude);
            $qb->andWhere('tbl.id NOT IN (:excludeList)')
               ->setParameter('excludeList', DataUtil::formatForStore($exclude));
        }
    
        $qb = $this->addSearchFilter($qb, $fragment);
    
        list($query, $count) = $this->getSelectWherePaginatedQuery($qb, $currentPage, $resultsPerPage);
    
        $result = $query->getResult();
    
        return array($result, $count);
    }
    
    /**
     * Adds where clause for search query.
     *
     * @param Doctrine\ORM\QueryBuilder $qb       Query builder to be enhanced.
     * @param string                    $fragment The fragment to search for.
     *
     * @return Doctrine\ORM\QueryBuilder Enriched query builder instance.
     */
    protected function addSearchFilter(QueryBuilder $qb, $fragment = '')
    {
        if ($fragment == '') {
            return $qb;
        }
    
        $fragment = DataUtil::formatForStore($fragment);
        $fragmentIsNumeric = is_numeric($fragment);
    
        $where = '';
        if (!$fragmentIsNumeric) {
        $where .= ((!empty($where)) ? ' OR ' : '');
        $where .= 'tbl.title LIKE \'%' . $fragment . '%\'';
        $where .= ((!empty($where)) ? ' OR ' : '');
        $where .= 'tbl.description LIKE \'%' . $fragment . '%\'';
        $where .= ((!empty($where)) ? ' OR ' : '');
        $where .= 'tbl.uploadIcon = \'' . $fragment . '\'';
        } else {
        $where .= ((!empty($where)) ? ' OR ' : '');
        $where .= 'tbl.title LIKE \'%' . $fragment . '%\'';
        $where .= ((!empty($where)) ? ' OR ' : '');
        $where .= 'tbl.description LIKE \'%' . $fragment . '%\'';
        $where .= ((!empty($where)) ? ' OR ' : '');
        $where .= 'tbl.uploadIcon = \'' . $fragment . '\'';
        }
        $where = '(' . $where . ')';
    
        $qb->andWhere($where);
    
        return $qb;
    }

    /**
     * Returns query builder instance for a count query.
     *
     * @param string  $where    The where clause to use when retrieving the object count (optional) (default='').
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true).
     *
     * @return Doctrine\ORM\QueryBuilder Created query builder instance.
     * @TODO fix usage of joins; please remove the first line and test.
     */
    protected function getCountQuery($where = '', $useJoins = true)
    {
        $useJoins = false;
    
        $selection = 'COUNT(tbl.id) AS numCurrentStates';
        if ($useJoins === true) {
            $selection .= $this->addJoinsToSelection();
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($selection)
           ->from('MUTicket_Entity_CurrentState', 'tbl');
    
        if ($useJoins === true) {
            $this->addJoinsToFrom($qb);
        }
    
        if (!empty($where)) {
            $qb->where($where);
        }
    
        return $qb;
    }
    
    /**
     * Selects entity count with a given where clause.
     *
     * @param string  $where    The where clause to use when retrieving the object count (optional) (default='').
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true).
     *
     * @return integer amount of affected records
     */
    public function selectCount($where = '', $useJoins = true)
    {
        $qb = $this->getCountQuery($where, $useJoins);
        $query = $qb->getQuery();
        return $query->getSingleScalarResult();
    }


    /**
     * Checks for unique values.
     *
     * @param string $fieldName  The name of the property to be checked
     * @param string $fieldValue The value of the property to be checked
     * @param int    $excludeId  Id of current states to exclude (optional).
     *
     * @return boolean result of this check, true if the given current state does not already exist
     */
    public function detectUniqueState($fieldName, $fieldValue, $excludeId = 0)
    {
        $qb = $this->getCountQuery('', false);
        $qb->andWhere('tbl.' . $fieldName . ' = :' . $fieldName)
           ->setParameter($fieldName, DataUtil::formatForStore($fieldValue));
    
        $qb = $this->addExclusion($qb, $excludeId);
    
        $query = $qb->getQuery();
    
        $count = $query->getSingleScalarResult();
    
        return ($count == 0);
    }

    /**
     * Builds a generic Doctrine query supporting WHERE and ORDER BY.
     *
     * @param string  $where    The where clause to use when retrieving the collection (optional) (default='').
     * @param string  $orderBy  The order-by clause to use when retrieving the collection (optional) (default='').
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true).
     * @param boolean $slimMode If activated only some basic fields are selected without using any joins (optional) (default=false).
     *
     * @return Doctrine\ORM\QueryBuilder query builder instance to be further processed
     */
    protected function _intBaseQuery($where = '', $orderBy = '', $useJoins = true, $slimMode = false)
    {
        // normally we select the whole table
        $selection = 'tbl';
    
        if ($slimMode === true) {
            // but for the slim version we select only the basic fields, and no joins
    
            $titleField = $this->getTitleFieldName();
            $selection = 'tbl.id';
            if ($titleField != '') {
                $selection .= ', tbl.' . $titleField;
            }
            $useJoins = false;
        }
    
        if ($useJoins === true) {
            $selection .= $this->addJoinsToSelection();
        }
    
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($selection)
           ->from('MUTicket_Entity_CurrentState', 'tbl');
    
        if ($useJoins === true) {
            $this->addJoinsToFrom($qb);
        }
    
        $this->_intBaseQueryAddWhere($qb, $where);
        $this->_intBaseQueryAddOrderBy($qb, $orderBy);
    
        return $qb;
    }

    /**
     * Adds WHERE clause to given query builder.
     *
     * @param Doctrine\ORM\QueryBuilder $qb    Given query builder instance.
     * @param string                    $where The where clause to use when retrieving the collection (optional) (default='').
     *
     * @return Doctrine\ORM\QueryBuilder query builder instance to be further processed
     */
    protected function _intBaseQueryAddWhere(QueryBuilder $qb, $where = '')
    {
        if (!empty($where)) {
            $qb->where($where);
        }
    
        $onlyOwn = (int) FormUtil::getPassedValue('own', 0, 'GETPOST');
        if ($onlyOwn == 1) {
            $uid = UserUtil::getVar('uid');
            $qb->andWhere('tbl.createdUserId = :creator')
               ->setParameter('creator', DataUtil::formatForStore($uid));
        }
    
        return $qb;
    }

    /**
     * Adds ORDER BY clause to given query builder.
     *
     * @param Doctrine\ORM\QueryBuilder $qb      Given query builder instance.
     * @param string                    $orderBy The order-by clause to use when retrieving the collection (optional) (default='').
     *
     * @return Doctrine\ORM\QueryBuilder query builder instance to be further processed
     */
    protected function _intBaseQueryAddOrderBy(QueryBuilder $qb, $orderBy = '')
    {
        if ($orderBy == 'RAND()') {
            // random selection
            $idValues = $this->getIdentifierListForRandomSorting();
            $qb->andWhere('tbl.id IN (:idValues)')
               ->setParameter('idValues', DataUtil::formatForStore($idValues));
    
            // no specific ordering in the main query for random items
            $orderBy = '';
        }
    
        // add order by clause
        if (!empty($orderBy)) {
            if (strpos($orderBy, '.') === false) {
                $orderBy = 'tbl.' . $orderBy;
            }
            $qb->add('orderBy', $orderBy);
        }
    
        return $qb;
    }

    /**
     * Retrieves a random list of identifiers.
     *
     * @return array Collected identifiers.
     */
    protected function getIdentifierListForRandomSorting()
    {
        $idList = array();
    
        // query all primary keys in slim mode without any joins
        $allEntities = $this->selectWhere('', '', false, true);
    
        if (!$allEntities || !is_array($allEntities) || !count($allEntities)) {
            return $idList;
        }
    
        foreach ($allEntities as $entity) {
            $idList[] = $entity['id'];
        }
    
        // shuffle the id array
        shuffle($idList);
    
        return $idList;
    }

    /**
     * Retrieves Doctrine query from query builder, applying FilterUtil and other common actions.
     *
     * @param Doctrine\ORM\QueryBuilder $qb Query builder instance
     *
     * @return Doctrine\ORM\Query query instance to be further processed
     */
    protected function getQueryFromBuilder(QueryBuilder $qb)
    {
        $query = $qb->getQuery();
    
        // TODO - see https://github.com/zikula/core/issues/118
        // use FilterUtil to support generic filtering
        //$fu = new FilterUtil('MUTicket', $this);
    
        // you could set explicit filters at this point, something like
        // $fu->setFilter('type:eq:' . $args['type'] . ',id:eq:' . $args['id']);
        // supported operators: eq, ne, like, lt, le, gt, ge, null, notnull
    
        // process request input filters and add them to the query.
        //$fu->enrichQuery($query);
    
    
        return $query;
    }

    /**
     * Helper method to add join selections.
     *
     * @return String Enhancement for select clause.
     */
    protected function addJoinsToSelection()
    {
        $selection = '';
    
        return $selection;
    }
    
    /**
     * Helper method to add joins to from clause.
     *
     * @param Doctrine\ORM\QueryBuilder $qb query builder instance used to create the query.
     *
     * @return String Enhancement for from clause.
     */
    protected function addJoinsToFrom(QueryBuilder $qb)
    {
    
        return $qb;
    }
}
