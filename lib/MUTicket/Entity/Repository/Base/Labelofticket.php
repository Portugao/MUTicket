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
 * Repository class used to implement own convenience methods for performing certain DQL queries.
 *
 * This is the base repository class for the many to many relationship
 * between ticket and label entities.
 */
class MUTicket_Entity_Repository_Base_Labelofticket extends EntityRepository
{
    public function truncateTable()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->delete('MUTicket_Entity_Labelofticket', 'tbl');
        $query = $qb->getQuery();
        $query->execute();
    }
}
