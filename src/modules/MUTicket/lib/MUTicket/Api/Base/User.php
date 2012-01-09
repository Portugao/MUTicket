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
 * This is the User api helper class.
 */
class MUTicket_Api_Base_User extends Zikula_AbstractApi
{
    /**
     * get available User panel links
     *
     * @return array Array of admin links
     */
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'main'),
                             'text' => $this->__('Backend'),
                             'title' => $this->__('Switch to administration area.'),
                             'class' => 'z-icon-es-options');
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url($this->name, 'user', 'view', array('ot' => 'ticket')),
                             'text' => $this->__('Tickets'),
                             'title' => $this->__('Ticket list'));
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url($this->name, 'user', 'view', array('ot' => 'rating')),
                             'text' => $this->__('Ratings'),
                             'title' => $this->__('Rating list'));
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url($this->name, 'user', 'view', array('ot' => 'supporter')),
                             'text' => $this->__('Supporters'),
                             'title' => $this->__('Supporter list'));
        }
        return $links;
    }

    /**
     * Form custom url string
     *
     * @return       string custom url string
     */
    public function encodeurl($args)
    {
        // check if we have the required input
        if (!is_array($args) || !isset($args['modname']) || !isset($args['func'])) {
            return LogUtil::registerArgsError();
        }

        // set default values
        if (!isset($args['type'])) {
            $args['type'] = 'user';
        }
        if (!isset($args['args'])) {
            $args['args'] = array();
        }

        // return if function url scheme is not being customised
        $customFuncs = array('view', 'display');
        if (!in_array($args['func'], $customFuncs)) {
            return false;
        }

        // reference to current language
        $lang = ZLanguage::getLanguageCode();

        // initialise url routing rules
        $routerFacade = new MUTicket_RouterFacade();
        // get router itself for convenience
        $router = $routerFacade->getRouter();

        // initialise object type
        $utilArgs = array('controller' => 'user', 'action' => 'encodeurl');
        $allowedObjectTypes = MUTicket_Util_Controller::getObjectTypes('api', $utilArgs);
        $objectType = ((isset($args['args']['ot']) && in_array($args['args']['ot'], $allowedObjectTypes)) ? $args['args']['ot'] : MUTicket_Util_Controller::getDefaultObjectType('api', $utilArgs));

        // initialise group folder
        $groupFolder = $routerFacade->getGroupingFolderFromObjectType($objectType, $args['func'], $args['args']);

        // start pre processing

        // convert object type to group folder
        $args['args']['ot'] = $groupFolder;

        // handle special templates
        $displayDefaultEnding = System::getVar('shorturlsext', 'html');
        $endingPrefix = ($args['func'] == 'view') ? '.' : '';
        foreach (array('csv', 'rss', 'atom', 'xml', 'pdf', 'json') as $ending) {
            if (!isset($args['args']['use' . $ending . 'ext'])) {
                continue;
            }
            if ($args['args']['use' . $ending . 'ext'] == '1') {
                $args['args'][$args['func'] . 'ending'] = $endingPrefix . $ending;
            }
            unset($args['args']['use' . $ending . 'ext']);
        }
        // fallback to default templates
        if (!isset($args['args'][$args['func'] . 'ending'])) {
            if ($args['func'] == 'view') {
                $args['args'][$args['func'] . 'ending'] = '';//'/';
            }
            else if ($args['func'] == 'display') {
                $args['args'][$args['func'] . 'ending'] = $displayDefaultEnding;
            }
        }

        if ($args['func'] == 'view') {
            // TODO filter views (e.g. /orders/customer/mr-smith.csv)
            /**
            $filterEntities = array('customer', 'region', 'federalstate', 'country');
            foreach ($filterEntities as $filterEntity) {
                $filterField = $filterEntity . 'id';
                if (!isset($args['args'][$filterField]) || !$args['args'][$filterField]) {
                    continue;
                }
                $filterId = $args['args'][$filterField];
                unset($args['args'][$filterField]);

                $filterGroupFolder = $routerFacade->getGroupingFolderFromObjectType($filterEntity, 'display', $args['args']);
                $filterSlug = $routerFacade->getFormattedSlug($filterEntity, 'display', $args['args'], $filterId);
                $result .= $filterGroupFolder . '/' . $filterSlug .'/';
                break;
            }
            */
        }
        elseif ($args['func'] == 'display') {
            // determine given id
            $id = 0;
            foreach (array('id', strtolower($objectType) . 'id', 'objectid') as $idFieldName) {
                if (isset($args['args'][$idFieldName])) {
                    $id = $args['args'][$idFieldName];
                    unset($args['args'][$idFieldName]);
                }
            }

            $slugTitle = '';
            if ($id > 0) {
                $slugTitle = $routerFacade->getFormattedSlug($objectType, $args['func'], $args['args'], $id);
            }

            if (!empty($slugTitle) && $slugTitle != $id) {
                // add slug expression
                $args['args']['title'] = $slugTitle;
            }
            else {
                // readd id
                $args['args']['id'] = $id;
            }
        }

        // add func as first argument
        $routerArgs = array_merge(array('func' => $args['func']), $args['args']);

        // now create url based on params
        $result = $router->generate(null, $routerArgs);

        // post processing
        if (
            ($args['func'] == 'view' && !empty($args['args']['viewending']))
            || $args['func'] == 'display') {
            // check if url ends with a trailing slash
            if (substr($result, -1) == '/') {
                // remove the trailing slash
                $result = substr($result, 0, strlen($result) - 1);
            }
        }

        // enforce url name of the module, but do only 1 replacement to avoid changing other params
        $modInfo = ModUtil::getInfoFromName('MUTicket');
        $result = preg_replace('/' . $modInfo['name'] . '/', $modInfo['url'], $result, 1);

        return $result;
    }

    /**
     * Decode the custom url string
     *
     * @return       bool true if successful, false otherwise
     */
    public function decodeurl($args)
    {
        // check we actually have some vars to work with
        if (!is_array($args) || !isset($args['vars']) || !is_array($args['vars']) || !count($args['vars'])) {
            return LogUtil::registerArgsError();
        }

        // define the available user functions
        $funcs = array('main', 'view', 'display', 'edit', 'delete');

        // return if function url scheme is not being customised
        $customFuncs = array('view', 'display');

        // set the correct function name based on our input
        if (empty($args['vars'][2])) {
            // no func and no vars = main
            System::queryStringSetVar('func', 'main');
            return true;
        } else if (in_array($args['vars'][2], $funcs) && !in_array($args['vars'][2], $customFuncs)) {
            // normal url scheme, no need for special decoding
            return false;
        }

        $func = $args['vars'][2];

        // usually the language is in $args['vars'][0], except no mod name is in the url and we are set as start app
        $modInfo = ModUtil::getInfoFromName('MUTicket');
        $lang = (strtolower($args['vars'][0]) == $modInfo['url']) ? $args['vars'][1] : $args['vars'][0];

        // remove some unrequired parameters
        foreach ($_GET as $k => $v) {
            if (in_array($k, array('module', 'type', 'func', 'lang', 'ot')) === false) {
                unset($_GET[$k]);
            }
        }

        // process all args except language and module
        $urlVars = array_slice($args['vars'], 2); // all except [0] and [1]

        // get arguments as string
        $url = implode('/', $urlVars);

        // check if default view urls end with a trailing slash
        if ($func == 'view' && strpos($url, '.') === false && substr($url, -1) != '/') {
            // add missing trailing slash
            $url .= '/';
        }

        $isDefaultModule = (System::getVar('shorturlsdefaultmodule', '') == $modInfo['name']);
        if (!$isDefaultModule) {
            $url = $modInfo['url'] . '/' . $url;
        }

        // initialise url routing rules
        $routerFacade = new MUTicket_RouterFacade();
        // get router itself for convenience
        $router = $routerFacade->getRouter();

        // read params out of url
        $parameters = $router->parse($url);
        //var_dump($parameters);

        if (!$parameters || !is_array($parameters)) {
            return false;
        }

        // post processing
        if (!isset($parameters['func'])) {
            $parameters['func'] = 'view';
        }

        $func = $parameters['func'];
        // convert group folder to object type
        $parameters['ot'] = $routerFacade->getObjectTypeFromGroupingFolder($parameters['ot'], $func);

        // handle special templates
        $displayDefaultEnding = System::getVar('shorturlsext', 'html');
        $endingPrefix = ($func == 'view') ? '.' : '';
        if (isset($parameters[$func . 'ending']) && !empty($parameters[$func . 'ending']) && $parameters[$func . 'ending'] != ($endingPrefix . $displayDefaultEnding)) {
            if ($func == 'view') {
                $parameters[$func . 'ending'] = str_replace($endingPrefix, '', $parameters[$func . 'ending']);
            }
            $parameters['use' . $parameters[$func . 'ending'] . 'ext'] = '1';
            unset($parameters[$func . 'ending']);
        }

        // rename id to objid (primary key for display pages, optional filter id for view pages)
        /* may be obsolete now
        if (isset($parameters['id'])) {
            $parameters[strtolower($parameters['ot']) . 'id'] = $parameters['id'];
            unset($parameters['id']);
        }*/

        // write vars to GET
        foreach ($parameters as $k => $v) {
            System::queryStringSetVar($k, $v);
        }

        return true;
    }
}
