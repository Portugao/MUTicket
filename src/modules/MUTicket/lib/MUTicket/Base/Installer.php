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
 * Installer base class
 */
class MUTicket_Base_Installer extends Zikula_AbstractInstaller
{
    /**
     * Install the MUTicket application.
     *
     * @return boolean True on success, or false.
     */
    public function install()
    {
        $basePath = MUTicket_Util_Controller::getFileBaseFolder('ticket', 'images');
        if (!is_dir($basePath)) {
            return LogUtil::registerError($this->__f('The upload folder "%s" does not exist. Please create it before installing this application.', array($basePath)));
        }
        if (!is_writable($basePath)) {
            return LogUtil::registerError($this->__f('The upload folder "%s" is not writable. Please change permissions accordingly before installing this application.', array($basePath)));
        }
        $basePath = MUTicket_Util_Controller::getFileBaseFolder('ticket', 'files');
        if (!is_dir($basePath)) {
            return LogUtil::registerError($this->__f('The upload folder "%s" does not exist. Please create it before installing this application.', array($basePath)));
        }
        if (!is_writable($basePath)) {
            return LogUtil::registerError($this->__f('The upload folder "%s" is not writable. Please change permissions accordingly before installing this application.', array($basePath)));
        }

        // create all tables from according entity definitions
        try {
            DoctrineHelper::createSchema($this->entityManager, $this->listEntityClasses());
        } catch (Exception $e) {
            if (System::isDevelopmentMode()) {
                LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
            }
            return LogUtil::registerError($this->__f('An error was encountered while creating the tables for the %s module.', array($this->getName())));
        }

        // set up all our vars with initial values
        $this->setVar('supportergroup', '');
        $this->setVar('rating', 1);

        // create the default data for MUTicket
        $this->createDefaultData();

        // add entries to category registry
        $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/Global');
        CategoryRegistryUtil::insertEntry('MUTicket', 'Ticket', 'Main', $rootcat['id']);

        // register persistent event handlers
        $this->registerPersistentEventHandlers();

        // register hook subscriber bundles
        HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());


        // initialisation successful
        return true;
    }

    /**
     * Upgrade the MUTicket application from an older version.
     *
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param integer $oldversion Version to upgrade from.
     *
     * @return boolean True on success, false otherwise.
     */
    public function upgrade($oldversion)
    {
    /*
        // Upgrade dependent on old version number
        switch ($oldversion) {
            case 1.0.0:
                // do something
                // ...
                // update the database schema
                try {
                    DoctrineHelper::updateSchema($this->entityManager, $this->listEntityClasses());
                } catch (Exception $e) {
                    if (System::isDevelopmentMode()) {
                        LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
                    }
                    return LogUtil::registerError($this->__f('An error was encountered while dropping the tables for the %s module.', array($this->getName())));
                }
        }
    */

        // update successful
        return true;
    }

    /**
     * Uninstall MUTicket.
     *
     * @return boolean True on success, false otherwise.
     */
    public function uninstall()
    {
        // delete stored object workflows
        $result = Zikula_Workflow_Util::deleteWorkflowsForModule($this->getName());
        if ($result === false) {
            return LogUtil::registerError($this->__f('An error was encountered while removing stored object workflows for the %s module.', array($this->getName())));
        }

        try {
            DoctrineHelper::dropSchema($this->entityManager, $this->listEntityClasses());
        } catch (Exception $e) {
            if (System::isDevelopmentMode()) {
                LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
            }
            return LogUtil::registerError($this->__f('An error was encountered while dropping the tables for the %s module.', array($this->getName())));
        }

        // unregister persistent event handlers
        EventUtil::unregisterPersistentModuleHandlers('MUTicket');

        // unregister hook subscriber bundles
        HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());


        // remove all module vars
        $this->delVars();

        // remove category registry entries
        ModUtil::dbInfoLoad('Categories');
        DBUtil::deleteWhere('categories_registry', "modname = 'MUTicket'");

        // deletion successful
        return true;
    }

    /**
     * Build array with all entity classes for MUTicket.
     *
     * @return array list of class names.
     */
    protected function listEntityClasses()
    {
        $classNames = array();
        $classNames[] = 'MUTicket_Entity_Ticket';
        $classNames[] = 'MUTicket_Entity_TicketCategory';
        $classNames[] = 'MUTicket_Entity_Rating';
        $classNames[] = 'MUTicket_Entity_Supporter';

        return $classNames;
    }
    /**
     * Create the default data for MUTicket.
     *
     * @return void
     */
    protected function createDefaultData()
    {
        // Ensure that tables are cleared
        $this->entityManager->transactional(function($entityManager) {
            $entityManager->getRepository('MUTicket_Entity_Ticket')->truncateTable();
            $entityManager->getRepository('MUTicket_Entity_Rating')->truncateTable();
            $entityManager->getRepository('MUTicket_Entity_Supporter')->truncateTable();
        });

        // Insertion successful
        return true;
    }

    /**
     * Register persistent event handlers.
     * These are listeners for external events of the core and other modules.
     */
    protected function registerPersistentEventHandlers()
    {
        // core
        EventUtil::registerPersistentModuleHandler('MUTicket', 'api.method_not_found', array('MUTicket_Listener_Core', 'apiMethodNotFound'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'core.preinit', array('MUTicket_Listener_Core', 'preInit'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'core.init', array('MUTicket_Listener_Core', 'init'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'core.postinit', array('MUTicket_Listener_Core', 'postInit'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'controller.method_not_found', array('MUTicket_Listener_Core', 'controllerMethodNotFound'));

        // installer
        EventUtil::registerPersistentModuleHandler('MUTicket', 'installer.module.installed', array('MUTicket_Listener_Installer', 'moduleInstalled'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'installer.module.upgraded', array('MUTicket_Listener_Installer', 'moduleUpgraded'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'installer.module.uninstalled', array('MUTicket_Listener_Installer', 'moduleUninstalled'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'installer.subscriberarea.uninstalled', array('MUTicket_Listener_Installer', 'subscriberAreaUninstalled'));

        // modules
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module_dispatch.postloadgeneric', array('MUTicket_Listener_ModuleDispatch', 'postLoadGeneric'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module_dispatch.preexecute', array('MUTicket_Listener_ModuleDispatch', 'preExecute'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module_dispatch.postexecute', array('MUTicket_Listener_ModuleDispatch', 'postExecute'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module_dispatch.custom_classname', array('MUTicket_Listener_ModuleDispatch', 'customClassname'));

        // mailer
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module.mailer.api.sendmessage', array('MUTicket_Listener_Mailer', 'sendMessage'));

        // page
        EventUtil::registerPersistentModuleHandler('MUTicket', 'pageutil.addvar_filter', array('MUTicket_Listener_Page', 'pageutilAddvarFilter'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'system.outputfilter', array('MUTicket_Listener_Page', 'systemOutputfilter'));

        // errors
        EventUtil::registerPersistentModuleHandler('MUTicket', 'setup.errorreporting', array('MUTicket_Listener_Errors', 'setupErrorReporting'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'systemerror', array('MUTicket_Listener_Errors', 'systemError'));

        // theme
        EventUtil::registerPersistentModuleHandler('MUTicket', 'theme.preinit', array('MUTicket_Listener_Theme', 'preInit'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'theme.init', array('MUTicket_Listener_Theme', 'init'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'theme.load_config', array('MUTicket_Listener_Theme', 'loadConfig'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'theme.prefetch', array('MUTicket_Listener_Theme', 'preFetch'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'theme.postfetch', array('MUTicket_Listener_Theme', 'postFetch'));

        // view
        EventUtil::registerPersistentModuleHandler('MUTicket', 'view.init', array('MUTicket_Listener_View', 'init'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'view.postfetch', array('MUTicket_Listener_View', 'postFetch'));

        // user login
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module.users.ui.login.started', array('MUTicket_Listener_UserLogin', 'started'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module.users.ui.login.veto', array('MUTicket_Listener_UserLogin', 'veto'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module.users.ui.login.succeeded', array('MUTicket_Listener_UserLogin', 'succeeded'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module.users.ui.login.failed', array('MUTicket_Listener_UserLogin', 'failed'));

        // user logout
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module.users.ui.logout.succeeded', array('MUTicket_Listener_UserLogout', 'succeeded'));

        // user
        EventUtil::registerPersistentModuleHandler('MUTicket', 'user.gettheme', array('MUTicket_Listener_User', 'getTheme'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'user.account.create', array('MUTicket_Listener_User', 'create'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'user.account.update', array('MUTicket_Listener_User', 'update'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'user.account.delete', array('MUTicket_Listener_User', 'delete'));

        // registration
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module.users.ui.registration.started', array('MUTicket_Listener_UserRegistration', 'started'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module.users.ui.registration.succeeded', array('MUTicket_Listener_UserRegistration', 'succeeded'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module.users.ui.registration.failed', array('MUTicket_Listener_UserRegistration', 'failed'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'user.registration.create', array('MUTicket_Listener_UserRegistration', 'create'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'user.registration.update', array('MUTicket_Listener_UserRegistration', 'update'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'user.registration.delete', array('MUTicket_Listener_UserRegistration', 'delete'));

        // users module
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module.users.config.updated', array('MUTicket_Listener_Users', 'configUpdated'));

        // group
        EventUtil::registerPersistentModuleHandler('MUTicket', 'group.create', array('MUTicket_Listener_Group', 'create'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'group.update', array('MUTicket_Listener_Group', 'update'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'group.delete', array('MUTicket_Listener_Group', 'delete'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'group.adduser', array('MUTicket_Listener_Group', 'addUser'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'group.removeuser', array('MUTicket_Listener_Group', 'removeUser'));

        // special purposes and 3rd party api support
        EventUtil::registerPersistentModuleHandler('MUTicket', 'get.pending_content', array('MUTicket_Listener_ThirdParty', 'pendingContentListener'));
        EventUtil::registerPersistentModuleHandler('MUTicket', 'module.content.gettypes', array('MUTicket_Listener_ThirdParty', 'contentGetTypes'));
    }
}
