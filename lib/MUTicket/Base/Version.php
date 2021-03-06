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
 * Version information base class.
 */
class MUTicket_Base_Version extends Zikula_AbstractVersion
{
    /**
     * Retrieves meta data information for this application.
     *
     * @return array List of meta data.
     */
    public function getMetaData()
    {
        $meta = array();
        // the current module version
        $meta['version']              = '1.1.0';
        // the displayed name of the module
        $meta['displayname']          = $this->__('M u ticket');
        // the module description
        $meta['description']          = $this->__('M u ticket module generated by ModuleStudio 0.6.1.');
        //! url version of name, should be in lowercase without space
        $meta['url']                  = $this->__('muticket');
        // core requirement
        $meta['core_min']             = '1.3.5'; // requires minimum 1.3.5
        $meta['core_max']             = '1.3.5'; // not ready for 1.3.6 yet

        // define special capabilities of this module
        $meta['capabilities'] = array(
                          HookUtil::SUBSCRIBER_CAPABLE => array('enabled' => true)
/*,
                          HookUtil::PROVIDER_CAPABLE => array('enabled' => true), // TODO: see #15
                          'authentication' => array('version' => '1.0'),
                          'profile'        => array('version' => '1.0', 'anotherkey' => 'anothervalue'),
                          'message'        => array('version' => '1.0', 'anotherkey' => 'anothervalue')
*/
        );

        // permission schema
        $meta['securityschema'] = array(
            'MUTicket::' => '::',
            'MUTicket::Ajax' => '::',
            'MUTicket:ItemListBlock:' => 'Block title::',
            'MUTicket:Ticket:' => 'Ticket ID::',
            'MUTicket:Ticket:Ticket' => 'Ticket ID:Ticket ID:',
            'MUTicket:Rating:' => 'Rating ID::',
            'MUTicket:Ticket:Rating' => 'Ticket ID:Rating ID:',
            'MUTicket:Supporter:' => 'Supporter ID::',
            'MUTicket:CurrentState:' => 'CurrentState ID::',
            'MUTicket:Label:' => 'Label ID::',
            'MUTicket:Ticket:Label' => 'Ticket ID:Label ID:',
        );
        // DEBUG: permission schema aspect ends


        return $meta;
    }

    /**
     * Define hook subscriber bundles.
     */
    protected function setupHookBundles()
    {
        
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.muticket.ui_hooks.tickets', 'ui_hooks', __('muticket Tickets Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'muticket.ui_hooks.tickets.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'muticket.ui_hooks.tickets.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'muticket.ui_hooks.tickets.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'muticket.ui_hooks.tickets.validate_edit');
        // Validate input from an ui create/edit form (generally not used).
        $bundle->addEvent('validate_delete', 'muticket.ui_hooks.tickets.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'muticket.ui_hooks.tickets.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'muticket.ui_hooks.tickets.process_delete');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.muticket.filter_hooks.tickets', 'filter_hooks', __('muticket Tickets Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'muticket.filter_hooks.tickets.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.muticket.ui_hooks.ratings', 'ui_hooks', __('muticket Ratings Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'muticket.ui_hooks.ratings.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'muticket.ui_hooks.ratings.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'muticket.ui_hooks.ratings.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'muticket.ui_hooks.ratings.validate_edit');
        // Validate input from an ui create/edit form (generally not used).
        $bundle->addEvent('validate_delete', 'muticket.ui_hooks.ratings.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'muticket.ui_hooks.ratings.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'muticket.ui_hooks.ratings.process_delete');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.muticket.filter_hooks.ratings', 'filter_hooks', __('muticket Ratings Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'muticket.filter_hooks.ratings.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.muticket.ui_hooks.supporters', 'ui_hooks', __('muticket Supporters Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'muticket.ui_hooks.supporters.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'muticket.ui_hooks.supporters.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'muticket.ui_hooks.supporters.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'muticket.ui_hooks.supporters.validate_edit');
        // Validate input from an ui create/edit form (generally not used).
        $bundle->addEvent('validate_delete', 'muticket.ui_hooks.supporters.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'muticket.ui_hooks.supporters.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'muticket.ui_hooks.supporters.process_delete');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.muticket.filter_hooks.supporters', 'filter_hooks', __('muticket Supporters Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'muticket.filter_hooks.supporters.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.muticket.ui_hooks.currentstates', 'ui_hooks', __('muticket Current states Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'muticket.ui_hooks.currentstates.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'muticket.ui_hooks.currentstates.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'muticket.ui_hooks.currentstates.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'muticket.ui_hooks.currentstates.validate_edit');
        // Validate input from an ui create/edit form (generally not used).
        $bundle->addEvent('validate_delete', 'muticket.ui_hooks.currentstates.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'muticket.ui_hooks.currentstates.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'muticket.ui_hooks.currentstates.process_delete');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.muticket.filter_hooks.currentstates', 'filter_hooks', __('muticket Current states Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'muticket.filter_hooks.currentstates.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.muticket.ui_hooks.labels', 'ui_hooks', __('muticket Labels Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'muticket.ui_hooks.labels.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'muticket.ui_hooks.labels.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'muticket.ui_hooks.labels.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'muticket.ui_hooks.labels.validate_edit');
        // Validate input from an ui create/edit form (generally not used).
        $bundle->addEvent('validate_delete', 'muticket.ui_hooks.labels.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'muticket.ui_hooks.labels.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'muticket.ui_hooks.labels.process_delete');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.muticket.filter_hooks.labels', 'filter_hooks', __('muticket Labels Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'muticket.filter_hooks.labels.filter');
        $this->registerHookSubscriberBundle($bundle);

        
    }
}
