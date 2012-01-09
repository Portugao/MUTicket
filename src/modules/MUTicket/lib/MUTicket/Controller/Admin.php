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
 * This is the Admin controller class providing navigation and interaction functionality.
 */
class MUTicket_Controller_Admin extends MUTicket_Controller_Base_Admin
{
    /**
     * This method provides a generic handling of simple delete requests.
     *
     * @param string  $ot           Treated object type.
     * @param int     $id           Identifier of entity to be deleted.
     * @param boolean $confirmation Confirm the deletion, else a confirmation page is displayed.
     * @param string  $tpl          Name of alternative template (for alternative display options, feeds and xml output)
     * @param boolean $raw          Optional way to display a template instead of fetching it (needed for standalone output)
     * @return mixed Output.
     */
    public function delete($args)
    {
// DEBUG: permission check aspect starts
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_ADMIN));
// DEBUG: permission check aspect ends

        // parameter specifying which type of objects we are treating
        $objectType = (isset($args['ot']) && !empty($args['ot'])) ? $args['ot'] : $this->request->getGet()->filter('ot', 'ticket', FILTER_SANITIZE_STRING);
        $utilArgs = array('controller' => 'admin', 'action' => 'delete');
        if (!in_array($objectType, MUTicket_Util_Controller::getObjectTypes('controllerAction', $utilArgs))) {
            $objectType = MUTicket_Util_Controller::getDefaultObjectType('controllerAction', $utilArgs);
        }
        
		$idFields = ModUtil::apiFunc($this->name, 'selection', 'getIdFields', array('ot' => $objectType));        
        
        // retrieve identifier of the object we wish to delete
        $idValues = MUTicket_Util_Controller::retrieveIdentifier($this->request, $args, $objectType, $idFields);
        $hasIdentifier = MUTicket_Util_Controller::isValidIdentifier($idValues);

        $this->throwNotFoundUnless($hasIdentifier, $this->__('Error! Invalid identifier received.'));

        $entity = ModUtil::apiFunc($this->name, 'selection', 'getEntity', array('ot' => $objectType, 'id' => $idValues));
        $this->throwNotFoundUnless($entity != null, $this->__('No such item.'));

        $confirmation = (bool) (isset($args['confirmation']) && !empty($args['confirmation'])) ? $args['confirmation'] : $this->request->getPost()->filter('confirmation', false, FILTER_VALIDATE_BOOLEAN);

        if ($confirmation) {
            $this->checkCsrfToken();

            // TODO call pre delete validation hooks
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            $this->registerStatus($this->__('Done! Item deleted.'));
            // TODO call post delete process hooks

            // clear view cache to reflect our changes
            $this->view->clear_cache();

            // redirect to the list of the current object type
            $this->redirect(ModUtil::url($this->name, 'admin', 'view',
                                                                                        array('ot' => $objectType)));
        }

        $repository = $this->entityManager->getRepository('MUTicket_Entity_' . ucfirst($objectType));

        // assign the object we loaded above
        $this->view->assign($objectType, $entity)
                   ->assign($repository->getAdditionalTemplateParameters('controllerAction', $utilArgs));

        // fetch and return the appropriate template
        return MUTicket_Util_View::processTemplate($this->view, 'admin', $objectType, 'delete', $args);
    }
}
