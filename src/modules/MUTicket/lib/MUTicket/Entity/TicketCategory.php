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

use Doctrine\ORM\Mapping as ORM;

/**

 * Entity extension domain class storing ticket categories.
 *
 * This is the concrete category class for ticket entities.
 * @ORM\Entity(repositoryClass="MUTicket_Entity_Repository_Base_TicketCategory")
 * @ORM\Table(name="muticket_ticket_category",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="cat_unq",columns={"registryId", "categoryId", "entityId"})
 *     }
 * )
 */
class MUTicket_Entity_TicketCategory extends MUTicket_Entity_Base_TicketCategory
{
    // feel free to add your own methods here
}
