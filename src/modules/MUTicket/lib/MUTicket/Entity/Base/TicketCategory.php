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
 * This is the base category class for ticket entities.
 */
class MUTicket_Entity_Base_TicketCategory extends Zikula_Doctrine2_Entity_EntityCategory
{
    /**
     * @ORM\ManyToOne(targetEntity="MUTicket_Entity_Ticket", inversedBy="categories")
     * @ORM\JoinColumn(name="entityId", referencedColumnName="id")
     * @var MUTicket_Entity_Ticket
     */
    protected $entity;

    /**
     * Get reference to owning entity.
     *
     * @return MUTicket_Entity_Ticket
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set reference to owning entity.
     *
     * @param MUTicket_Entity_Ticket $entity
     */
    public function setEntity(/*MUTicket_Entity_Ticket */$entity)
    {
        $this->entity = $entity;
    }
}
