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
 * Generic item list content plugin base class
 */
class MUTicket_ContentType_Base_ItemList extends Content_AbstractContentType
{
    private $objectType;
    private $sorting;
    private $amount;
    private $template;
    private $filter;

    public function getModule()
    {
        return 'MUTicket';
    }

    public function getName()
    {
        return 'ItemList';
    }

    public function getTitle()
    {
        $dom = ZLanguage::getModuleDomain('MUTicket');
        return __('MUTicket items', $dom);
    }

    public function getDescription()
    {
        $dom = ZLanguage::getModuleDomain('MUTicket');
        return __('Display MUTicket items.', $dom);
    }

    public function loadData(&$data)
    {
        if (!isset($data['objectType']) || !in_array($data['objectType'], MUTicket_Util_Controller::getObjectTypes('contentType'))) {
            $data['objectType'] = MUTicket_Util_Controller::getDefaultObjectType('contentType');
        }

        $this->objectType = $data['objectType'];

        if (!isset($data['sorting'])) {
            $data['sorting'] = 'default';
        }
        if (!isset($data['amount'])) {
            $data['amount'] = 1;
        }
        if (!isset($data['template'])) {
            $data['template'] = 'itemlist_' . ucwords($this->objectType) . '_display.tpl';
        }
        if (!isset($data['filter'])) {
            $data['filter'] = '';
        }

        $this->sorting = $data['sorting'];
        $this->amount = $data['amount'];
        $this->template = $data['template'];
        $this->filter = $data['filter'];
    }

    public function display()
    {
        $dom = ZLanguage::getModuleDomain('MUTicket');
        ModUtil::initOOModule('MUTicket');

        $serviceManager = ServiceUtil::getManager();
        $entityManager = $serviceManager->getService('doctrine.entitymanager');
        $repository = $entityManager->getRepository('MUTicket_Entity_' . ucfirst($this->objectType));

        $idFields = ModUtil::apiFunc('MUTicket', 'selection', 'getIdFields', array('ot' => $objectType));

        $sortParam = '';
        if ($this->sorting == 'random') {
            $sortParam = 'RAND()';
        } elseif ($this->sorting == 'newest') {
            if (count($idFields) == 1) {
                $sortParam = $idFields[0] . ' DESC';
            } else {
                foreach ($idFields as $idField) {
                    if (!empty($sortParam)) {
                        $sortParam .= ', ';
                    }
                    $sortParam .= $idField . ' ASC';
                }
            }
        } elseif ($this->sorting == 'default') {
            $sortParam = $repository->getDefaultSortingField() . ' ASC';
        }

        $resultsPerPage = (($this->amount) ? $this->amount : 1);

        // get objects from database
        $selectionArgs = array(
            'ot' => $objectType,
            'where' => $this->filter,
            'orderBy' => $sortParam,
            'currentPage' => 1,
            'resultsPerPage' => $resultsPerPage
        );
        list($entities, $objectCount) = ModUtil::apiFunc('MUTicket', 'selection', 'getEntitiesPaginated', $selectionArgs);

        $this->view->setCaching(true);

        $data = array('objectType' => $this->objectType, 'sorting' => $this->sorting, 'amount' => $this->amount, 'filter' => $this->filter, 'template' => $this->template);

        // assign block vars and fetched data
        $this->view->assign('vars', $data)
                   ->assign('objectType', $this->objectType)
                   ->assign('items', $entities)
                   ->assign($repository->getAdditionalTemplateParameters('contentType'));

        $output = '';
        if (!empty($this->template) && $this->view->template_exists('contenttype/' . $this->template)) {
            $output = $this->view->fetch('contenttype/' . $this->template);
        }
        $templateForObjectType = str_replace('itemlist_', 'itemlist_' . ucwords($this->objectType) . '_', $this->template);
        if ($this->view->template_exists('contenttype/' . $templateForObjectType)) {
            $output = $this->view->fetch('contenttype/' . $templateForObjectType);
        } elseif ($this->view->template_exists('contenttype/' . $this->template)) {
            $output = $this->view->fetch('contenttype/' . $this->template);
        } else {
            $output = $this->view->fetch('contenttype/itemlist_display.tpl');
        }

        return $output;
    }

    public function displayEditing()
    {
        return $this->display();
    }

    public function getDefaultData()
    {
        return array('objectType' => 'ticket',
                     'sorting' => 'default',
                     'amount' => 1,
                     'template' => 'itemlist_display.tpl',
                     'filter' => '');
    }

    public function startEditing()
    {
        $dom = ZLanguage::getModuleDomain('MUTicket');
        array_push($this->view->plugins_dir, 'modules/MUTicket/templates/plugins');
    }
}
