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
 * @version Generated by ModuleStudio 0.5.2 (http://modulestudio.de) at Thu Sep 15 21:40:56 CEST 2011.
 */


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Repository class used to implement own convenience methods for performing certain DQL queries.
 *
 * This is the base repository class for rating entities.
 */
class MUTicket_Entity_Repository_Base_Rating extends EntityRepository
{
    /**
     * @var string The default sorting field/expression.
     */
    protected $defaultSortingField = 'ratingvalue';

    /**
     * Retrieves an array with all fields which can be used for sorting instances.
     *
     * @TODO to be refactored
     * @return array
     */
    public function getAllowedSortingFields()
    {
        return array(
                     'ticket',
                     'ratingvalue'

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
     * Helper method for truncating the table.
     * Used during installation when inserting default data.
     */
    public function truncateTable()
    {
        $query = $this->getEntityManager()
                 ->createQuery('DELETE MUTicket_Entity_Rating');
        $query->execute();
    }

    /**
     * Select object from the database.
     *
     * @param mixed   $id       The id (or array of ids) to use to retrieve the object (optional) (default=null).
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true).
     *
     * @return array|MUTicket_Entity_Rating retrieved data array or MUTicket_Entity_Rating instance
     */
    public function selectById($id = 0, $useJoins = true)
    {
        // check id parameter
        if ($id == 0) {
            return LogUtil::registerArgsError();
        }

        $where = '';
        if (is_array($id)) {
            foreach ($id as $fieldName => $fieldValue) {
                if (!empty($where)) {
                    $where .= ' AND ';
                }
                $where .= 'tbl.' . DataUtil::formatForStore($fieldName) . ' = \'' . DataUtil::formatForStore($fieldValue) . '\'';
            }
        } else {
            $where .= 'tbl.id = ' . DataUtil::formatForStore($id);
        }

        $query = $this->_intBaseQuery($where, '', $useJoins);

        return $query->getSingleResult();
    }


    /**
     * Select with a given where clause.
     *
     * @param string  $where    The where clause to use when retrieving the collection (optional) (default='').
     * @param string  $orderBy  The order-by clause to use when retrieving the collection (optional) (default='').
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true).
     *
     * @return ArrayCollection collection containing retrieved MUTicket_Entity_Rating instances
     */
    public function selectWhere($where = '', $orderBy = '', $useJoins = true)
    {
        $query = $this->_intBaseQuery($where, $orderBy, $useJoins);

        return $query->getResult();
    }

    /**
     * Select with a given where clause and pagination parameters.
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
        $query = $this->_intBaseQuery($where, $orderBy, $useJoins);

        $offset = ($currentPage-1) * $resultsPerPage;
        $query->setFirstResult($offset)
              ->setMaxResults($resultsPerPage);

        $result = $query->getResult();

        $count = $this->selectCount($where, $useJoins);

        return array($result, $count);
    }

    /**
     * Select entities by a given search fragment.
     *
     * @param string  $fragment       The fragment to search for.
     * @param string  $exclude        Comma separated list with ids to be excluded from search.
     * @param string  $orderBy        The order-by clause to use when retrieving the collection (optional) (default='').
     * @param integer $currentPage    Where to start selection
     * @param integer $resultsPerPage Amount of items to select
     * @param boolean $useJoins       Whether to include joining related objects (optional) (default=true).
     *
     * @return Array with retrieved collection and amount of total records affected by this query.
     */
    public function selectSearch($fragment = '', $exclude = array(), $orderBy = '', $currentPage = 1, $resultsPerPage = 25, $useJoins = true)
    {
        $where = '';
        if (count($exclude) > 0) {
            $exclude = DataUtil::formatForStore($exclude);
            $where .= 'tbl.id NOT IN (' . implode(', ', $exclude) . ')';
        }

        $fragment = DataUtil::formatForStore($fragment);

        $whereSub = '';
        $whereSub .= ((!empty($whereSub)) ? ' OR ' : '') . 'tbl.ratingvalue = \'' . $fragment . '\'';

        if (!empty($whereSub)) {
            $where .= ((!empty($where)) ? ' AND (' . $whereSub . ')' : $whereSub);
        }

        return $this->selectWherePaginated($where, $orderBy, $currentPage, $resultsPerPage, $useJoins);
    }

    /**
     * Select count with a given where clause.
     *
     * @param string  $where    The where clause to use when retrieving the object count (optional) (default='').
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true).
     *
     * @return integer amount of affected records
     * @TODO fix usage of joins; please remove the first line and test.
     */
    public function selectCount($where = '', $useJoins = true)
    {
        $useJoins = false;

        $selection = 'COUNT(tbl.id) AS numRatings';
        if ($useJoins === true) {
            $selection .= $this->addJoinsToSelection();
        }

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($selection)
           ->from('MUTicket_Entity_Rating', 'tbl');

        if ($useJoins === true) {
            $this->addJoinsToFrom($qb);
        }

        if (!empty($where)) {
            $qb->where($where);
        }

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Check for unique values.
     *
     * @param string $fieldName  The name of the property to be checked
     * @param string $fieldValue The value of the property to be checked
     * @param int    $excludeid  Id of ratings to exclude (optional).
     * @return boolean result of this check, true if the given rating does not already exist
     */
    public function detectUniqueState($fieldName, $fieldValue, $excludeid = 0)
    {
        $where = 'tbl.' . $fieldName . ' = \'' . DataUtil::formatForStore($fieldValue) . '\'';

        if ($excludeid > 0) {
            $where .= ' AND tbl.id != \'' . (int) DataUtil::formatForStore($excludeid) . '\'';
        }

        $count = $this->selectCount($where);
        return ($count == 0);
    }


    /**
     * Build a generic Doctrine query supporting WHERE and ORDER BY
     *
     * @param string  $where    The where clause to use when retrieving the collection (optional) (default='').
     * @param string  $orderBy  The order-by clause to use when retrieving the collection (optional) (default='').
     * @param boolean $useJoins Whether to include joining related objects (optional) (default=true).
     *
     * @return Doctrine\ORM\Query query instance to be further processed
     */
    protected function _intBaseQuery($where = '', $orderBy = '', $useJoins = true)
    {
        $selection = 'tbl' . (($orderBy == 'RAND()') ? ', rand() AS rand' : '');
        if ($useJoins === true) {
            $selection .= $this->addJoinsToSelection();
        }

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($selection)
           ->from('MUTicket_Entity_Rating', 'tbl');

        if ($useJoins === true) {
            $this->addJoinsToFrom($qb);
        }

        if (!empty($where)) {
            $qb->where($where);
        }

        // add order by clause
        if (!empty($orderBy)) {
            if ($orderBy == 'RAND()') {
                $qb->orderBy('rand', 'ASC');
            }
            else {
                $qb->add('orderBy', 'tbl.' . $orderBy);
            }
        }

        $query = $qb->getQuery();

// TODO - see http://code.zikula.org/core/ticket/3138
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
