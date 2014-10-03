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
 * Installer implementation class
 */
class MUTicket_Installer extends MUTicket_Base_Installer
{
    /**
     * Install the MUTicket application.
     *
     * @return boolean True on success, or false.
     */
    public function install()
    {
        parent::install();

        $dom = ZLanguage::getModuleDomain('MUTicket');

        $this->setVar('messageNewOwner', __('Hi supporter, here you get this ticket to work for the customer by yourself.', $dom));
        $this->setVar('messageDueDate', __('Dear Customer! We assume that we are able to clear your ticket until the given date', $dom));

        return true;
    }

    /**
     * Upgrade the MUTicket application from an older version.
     *
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param integer $oldVersion Version to upgrade from.
     *
     * @return boolean True on success, false otherwise.
     */
    public function upgrade($oldVersion)
    {

        // Upgrade dependent on old version number
        switch ($oldVersion) {
            case '1.0.0':

                $rating = $this->getVar('rating');
                $this->setVar('ratingAllowed', $rating);
                $this->delVar('rating');

                $dom = ZLanguage::getModuleDomain('MUTicket');
                 
                $this->setVar('supporterTickets', false);
                $this->setVar('messageNewOwner', __('Hi supporter, here you get this ticket to work for the customer by yourself.', $dom));
                $this->setVar('messageDueDate', __('Dear Customer! We assume that we are able to clear your ticket until the given date', $dom));
                 
                // ...
                // update the database schema
                try {
                    DoctrineHelper::updateSchema($this->entityManager, $this->listEntityClasses());
                } catch (\Exception $e) {
                    if (System::isDevelopmentMode()) {
                        LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
                    }
                    return LogUtil::registerError($this->__f('An error was encountered while dropping the tables for the %s extension.', array($this->getName())));
                }

                // we get serviceManager
                $serviceManager = ServiceUtil::getManager();
                // we get entityManager
                $entityManager = $serviceManager->getService('doctrine.entitymanager');

                // we set all entities of tickets, supporters and ratings to approved
                // we get repositories
                $ticketrepository = MUTicket_Util_Model::getTicketRepository();
                $supporterrepository = MUTicket_Util_Model::getSupporterRepository();
                $ratingrepository = MUTicket_Util_Model::getRatingRepository();

                $workflowHelper = new Zikula_Workflow('none', 'MUTicket');

                // we get all tickets
                $tickets = $ticketrepository->selectWhere();

                // we set each ticket to approved
                // and register a workflow
                foreach ($tickets as $ticket) {
                    $thisticket = $ticketrepository->selectById($ticket['id']);
                    $thisticket->setCurrentState(NULL);
                    $thisticket->setWorkflowState('approved');
                    $entityManager->flush();

                    $obj['__WORKFLOW__']['obj_table'] = 'ticket';
                    $obj['__WORKFLOW__']['obj_idcolumn'] = 'id';
                    $obj['id'] = $ticket['id'];
                    $workflowHelper->registerWorkflow($obj, 'approved');
                }

                // we get all ratings
                $ratings = $ratingrepository->selectWhere();

                // we set each rating to approved
                // and register a workflow
                foreach ($ratings as $rating) {
                    $thisrating = $ratingrepository->selectById($rating['id']);
                    $thisrating->setWorkflowState('approved');
                    $entityManager->flush();
                    $obj['__WORKFLOW__']['obj_table'] = 'rating';
                    $obj['__WORKFLOW__']['obj_idcolumn'] = 'id';
                    $obj['id'] = $rating['id'];
                    $workflowHelper->registerWorkflow($obj, 'approved');
                }

                // we get all supporters
                $supporters = $supporterrepository->selectWhere();

                // we set each supporter to approved
                // and register a workflow
                foreach ($supporters as $supporter) {
                    $obj['__WORKFLOW__']['obj_table'] = 'supporter';
                    $obj['__WORKFLOW__']['obj_idcolumn'] = 'id';
                    $obj['id'] = $supporter['id'];
                    $workflowHelper->registerWorkflow($obj, 'approved');
                }
                
                foreach ($supporters as $supporter) {
                    $thissupporter = $supporterrepository->selectById($supporter['id']);
                    $supportcats = $thissupporter->getSupportcats();
                    $supportcats = html_entity_decode($supportcats);
                    $thissupporter->setSupportcats($supportcats);
                    $thissupporter->setWorkflowState('approved');
                    $entityManager->flush();
                }
                
                case '1.1.0':
                    // for later update
        }


        // update successful
        return true;
    }
}
