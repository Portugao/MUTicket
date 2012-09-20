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
	 * Post initialise.
	 *
	 * Run after construction.
	 *
	 * @return void
	 */
	protected function postInitialize()
	{
		// Set caching to true by default.
		$this->view->setCaching(Zikula_View::CACHE_DISABLED);
	}

	/**
	 * This method is the default function, and is called whenever the application's
	 * User area is called without defining arguments.
	 *
	 * @return mixed Output.
	 */
	public function main($args)
	{
		// DEBUG: permission check aspect starts
		$this->throwForbiddenUnless(SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_OVERVIEW));
		// DEBUG: permission check aspect ends

		// return view template
		return $this->redirect(ModUtil::url($this->name, 'user', 'view', array('ot' => 'ticket', 'state' => 2)));
	}

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
		$objectType = (isset($args['ot']) && !empty($args['ot'])) ? $args['ot'] : $this->request->getGet()->filter('ot', 'ticket', FILTER_SANITIZE_STRING);
		$utilArgs = array('controller' => 'user', 'action' => 'view');
		if (!in_array($objectType, MUTicket_Util_Controller::getObjectTypes('controllerAction', $utilArgs))) {
			$objectType = MUTicket_Util_Controller::getDefaultObjectType('controllerAction', $utilArgs);
		}
		$repository = $this->entityManager->getRepository('MUTicket_Entity_' . ucfirst($objectType));

		$tpl = (isset($args['tpl']) && !empty($args['tpl'])) ? $args['tpl'] : $this->request->getGet()->filter('tpl', '', FILTER_SANITIZE_STRING);
		if ($tpl == 'tree') {
			$trees = ModUtil::apiFunc($this->name, 'selection', 'getAllTrees', array('ot' => $objectType));
			$this->view->assign('trees', $trees)
			->assign($repository->getAdditionalTemplateParameters('controllerAction', $utilArgs));
			// fetch and return the appropriate template
			return MUTicket_Util_View::processTemplate($this->view, 'user', $objectType, 'view', $args);
		}

		// parameter for used sorting field
		$sort = (isset($args['sort']) && !empty($args['sort'])) ? $args['sort'] : $this->request->getGet()->filter('sort', '', FILTER_SANITIZE_STRING);
		if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
			$sort = $repository->getDefaultSortingField();
		}

		// parameter for used sort order
		$sdir = (isset($args['sortdir']) && !empty($args['sortdir'])) ? $args['sortdir'] : $this->request->getGet()->filter('sortdir', '', FILTER_SANITIZE_STRING);
		$sdir = strtolower($sdir);
		if ($sdir != 'asc' && $sdir != 'desc') {
			$sdir = 'desc';
		}

		// convenience vars to make code clearer
		$currentUrlArgs = array('ot' => $objectType);

		// We rule the view depends on several parameters

		$func = $this->request->getGet()->filter('func','', FILTER_SANITIZE_STRING);
		$ot = $this->request->getGet()->filter('ot','', FILTER_SANITIZE_STRING);
		$rated = $this->request->getGet()->filter('rated', 0, FILTER_SANITIZE_NUMBER_INT);
		// We look for state - 2 is open, 3 is closed, if no state, state will get 1 for all
		$ticketstate = (int) $this->request->getGet()->filter('state', 1 , FILTER_SANITIZE_NUMBER_INT);

		// check for entity where parent_id is NULL
		if ($ot == 'ticket' && $func == 'view') {
			if (!empty($where)) {
				$where .= 'AND tbl.parent_id IS NULL';
			}
			else {
				$where = 'tbl.parent_id IS NULL';
			}
		}
		else {
			$where = $where;
		}
			

		if(isset($ticketstate)) {
			if (!empty($where) && $ticketstate != 1) {
				$where .= ' AND ';
			}
			if($ticketstate != 1) {
				if ($ticketstate == 2) {
					$where .= 'tbl.state = 1';
				}
				if ($ticketstate == 3) {
					$where .= 'tbl.state = 0';
				}
			}
		}

		// handle the view for users also supporters
		// users may see their own tickets and answers
		// supporters may see all

		// we check if a user is calling
		$type = (string) $this->request->getGet()->filter('type','' , FILTER_SANITIZE_STRING);
		// we check if user is logged in
		if (UserUtil::isLoggedIn() === true) {
			$uid = UserUtil::getVar('uid');
		}
		// guests get no access
		else {
			return LogUtil::registerPermissionError();
			//System::redirect($redirecturl);
		}

		// we check if the user is a supporter
		if (in_array($uid, MUTicket_Util_Model::getExistingSupporterUids($id = '')) === false) {

			if($type == 'user') {
				if (!empty($where)) {
					$where .= ' AND ';
				}
				if (!empty($where)) {
					$where .= 'tbl.createdUserId = \'' . DataUtil::formatForStore($uid) . '\'';
				}
				else {
					$where = 'tbl.createdUserId = \'' . DataUtil::formatForStore($uid) . '\'';
				}
			}
		}

		// We check for supportes that are active
		// If there is no supporter active, we show no link for new tickets
		// and no edit form for answers
		$supporteractive = MUTicket_Util_View::checkIfSupporters();
			
		// We check if user is supporter
		$kind = MUTicket_Util_View::userForRating();

		$selectionArgs = array(
            'ot' => $objectType,
            'where' => $where,
            'orderBy' => $sort . ' ' . $sdir
		);

		$showAllEntries = (int) (isset($args['all']) && !empty($args['all'])) ? $args['all'] : $this->request->getGet()->filter('all', 0, FILTER_VALIDATE_INT);
		$this->view->assign('showAllEntries', $showAllEntries);
		if ($showAllEntries == 1) {
			// item list without pagination
			$entities = ModUtil::apiFunc($this->name, 'selection', 'getEntities', $selectionArgs);
			$objectCount = count($entities);
			$currentUrlArgs['all'] = 1;
		} else {
			// item list with pagination

			// the current offset which is used to calculate the pagination
			$currentPage = (int) (isset($args['pos']) && !empty($args['pos'])) ? $args['pos'] : $this->request->getGet()->filter('pos', 1, FILTER_VALIDATE_INT);

			// the number of items displayed on a page for pagination
			$resultsPerPage = (int) (isset($args['num']) && !empty($args['num'])) ? $args['num'] : $this->request->getGet()->filter('num', 0, FILTER_VALIDATE_INT);
			if ($resultsPerPage == 0) {
				$csv = (int) (isset($args['usecsv']) && !empty($args['usecsv'])) ? $args['usecsv'] : $this->request->getGet()->filter('usecsvext', 0, FILTER_VALIDATE_INT);
				$resultsPerPage = ($csv == 1) ? 999999 : $this->getVar('pagesize', 10);
			}

			$selectionArgs['currentPage'] = $currentPage;
			$selectionArgs['resultsPerPage'] = $resultsPerPage;
			list($entities, $objectCount) = ModUtil::apiFunc($this->name, 'selection', 'getEntitiesPaginated', $selectionArgs);

			$this->view->assign('currentPage', $currentPage)
			->assign('pager', array('numitems'     => $objectCount,
                                               'itemsperpage' => $resultsPerPage));
		}

		// build ModUrl instance for display hooks
		$currentUrlObject = new Zikula_ModUrl($this->name, 'user', 'view', ZLanguage::getLanguageCode(), $currentUrlArgs);

		// assign the object data, sorting information and details for creating the pager
		$this->view->assign('items', $entities)
		->assign('sort', $sort)
		->assign('sdir', $sdir)
		->assign('state', $ticketstate)
		->assign('kind', $kind)
		->assign('supporteractive', $supporteractive)
		->assign('currentUrlObject', $currentUrlObject)
		->assign($repository->getAdditionalTemplateParameters('controllerAction', $utilArgs));

		// fetch and return the appropriate template
		return MUTicket_Util_View::processTemplate($this->view, 'user', $objectType, 'view', $args);

	}

	/**
	 * This method provides a generic item detail view.
	 *
	 * @param string  $kind         kind of logged in user - 0 for supporter, 1 for user
	 * @return parent display()
	 */
	public function display($args)
	{
		// We check if ticket is a parent ticket
		// If the ticket is not a parent ticket but an answer
		// we redirect to view of open parent tickets
		$id = $this->request->query->filter('id', 0, FILTER_SANITIZE_NUMBER_INT);
		$repository = MUTicket_Util_Model::getTicketRepository();
		$entity = $repository->selectById($id);
		if ($entity['parent_id'] > 0) {
			$url = ModUtil::url($this->name, 'user', 'display', array('ot' => 'ticket', 'id' => $entity['parent_id']));
			LogUtil::registerStatus(__('Sorry! Only parent tickets are directly available in display!'));
			$this->redirect($url);
		}
			
		// Is it allowed to rate
		$rating = ModUtil::getVar($this->name, 'rating');
			
		// May this user rate
		$kind = MUTicket_Util_View::userForRating();
			
		// We check for supportes that are active
		// If there is no supporter active, we show no link for new tickets
		// and no edit form for answers
		$supporteractive = MUTicket_Util_View::checkIfSupporters();

		$this->view->assign('kind', $kind)
		->assign('rating', $rating)
		->assign('supporteractive', $supporteractive);

		return parent::display($args);
	}

	/**
	 * This method provides a generic handling of all edit requests.
	 *
	 * @param string  $ot           Treated object type.
	 * @param string  $tpl          Name of alternative template (for alternative display options, feeds and xml output)
	 * @param boolean $raw          Optional way to display a template instead of fetching it (needed for standalone output)
	 * @return mixed Output.
	 */
	public function edit($args)
	{
		// DEBUG: permission check aspect starts
		$this->throwForbiddenUnless(SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_EDIT));
		// DEBUG: permission check aspect ends

		// parameter specifying which type of objects we are treating
		$objectType = (isset($args['ot']) && !empty($args['ot'])) ? $args['ot'] : $this->request->getGet()->filter('ot', 'ticket', FILTER_SANITIZE_STRING);
		$utilArgs = array('controller' => 'user', 'action' => 'edit');
		if (!in_array($objectType, MUTicket_Util_Controller::getObjectTypes('controllerAction', $utilArgs))) {
			$objectType = MUTicket_Util_Controller::getDefaultObjectType('controllerAction', $utilArgs);
		}

		// create new Form reference
		$view = FormUtil::newForm($this->name, $this);

		// build form handler class name
		$handlerClass = 'MUTicket_Form_Handler_User_' . ucfirst($objectType) . '_Edit';

		// execute form using supplied template and page event handler
		return $view->execute('user/' . $objectType . '/edit.tpl', new $handlerClass());
	}

}
