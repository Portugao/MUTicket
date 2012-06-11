<?php
/**
 * Eternizer.
 *
 * @copyright Michael Ueberschaer
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package Eternizer
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Wed Jan 04 16:43:44 CET 2012.
 */

/**
 * Utility base class for settings helper methods.
 */
class MUTicket_Util_Base_Settings extends Zikula_AbstractBase
{

	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $args
	 */
	public function handleModvarsPostPersist($args)
	{
		$serviceManager = ServiceUtil::getManager();
		$handler = new Zikula_Form_View($serviceManager, 'MUTicket');

		$lang = ZLanguage::getLanguageCode();

		$id = $args['id'];
        $title = $args['title'];
		if ($title == '') {
			$title = $handler->__('No title');
		}
		$text = $args['text'];	
		$parentid = $args['parentid'];
		$categories = $args['categories'];

		$ticketcategory .= $handler->__('Category: ');

		if (count($categories) > 0) {
			foreach ($categories as $category) {
				$name = $category->getCategory()->getName();
				$display_name = $category->getCategory()->getDisplayName();
				$id = $category->getCategory()->getId();

				if (isset($display_name[$lang]) && !empty($display_name[$lang])) {
					$ticketcategory .= $display_name[$lang];
						
				} else if (isset($name) && !empty($name)) {
					$ticketcategory .= $name;
				}
					
				$ticketcategory2 = $id;
			}
		}
		else {
			$ticketcategory = '';
			$ticketcategory2 = '';
		}

		// Get actual userid
		$userid = $this->getCreatedUserId();

		// Get supporter ids
		$supporteruids = MUTicket_Util_Model::getExistingSupporterUids();

		// Check if is array
		if (is_array($supporteruids)) {
			// Check if user is a supporter
			if (in_array($userid, $supporteruids)) {
				$kind = 'Supporter';
			}
			else {
				$kind = 'Customer';
			}
		}
		else {
			if ($userid == $supporteruids) {
				$kind ='Supporter';
			}
			else {
				$kind ='Customer';
			}
		}

		if ($parentid === NULL) {
			$entry = $handler->__('A new ticket on ');
		}
		else {
			if ($kind == 'Supporter') {
				$entry = $handler->__('An answer to your ticket on ');
			}
			else {
				$entry = $handler->__('An answer to a ticket on ');
			}
		}

		$host = System::serverGetVar('HTTP_HOST') . '/';
		$url = 'http://' . $host . ModUtil::url('MUTicket', 'user', 'display', array('ot' => 'ticket', 'id' => $parentid));
		$editurl = 'http://' . $host . ModUtil::url('Eternizer', 'admin', 'edit', array('ot' => 'entry', 'id' => $id));

		$from = ModUtil::getVar('ZConfig', 'sitename') . ' ';
		$fromaddress = ModUtil::getVar('ZConfig', 'adminmail');

		if ($kind == 'Customer') {
			$toaddress = MUTicket_Util_Model::getSupporterMails($ticketcategory2);			
			$messagecontent = MUTicket_Util_Base_Settings::getMailContent($from, $fromaddress, $toaddress, $entry, $ticketcategory, $title, $text, $url);
		}
		// TODO get mail of parent ticket creater
		if ($kind == 'Supporter') {
			$toaddress = MUTicket_Util_Base_Settings::getMailAddressOfUser($parentid);		
			$messagecontent = MUTicket_Util_Base_Settings::getMailContent($from, $fromaddress, $toaddress, $entry, $ticketcategory, $title, $text, $url);
		}

		// We send a mail if an email address is saved
		if ($toaddress != '') {

			if (!ModUtil::apiFunc('Mailer', 'user', 'sendmessage', $messagecontent)) {
				LogUtil::registerError($handler->__('Unable to send message'));
			}
		}

		// Formating of status text
		if ($kind == 'Customer') {
			$message = $handler->__('Your ticket was saved and an email sent to our support!');
		}
		else {
			$message = $handler->__('Your support answer was saved and an email sent to the customer!');	
		}

		LogUtil::registerStatus($message);

		return true;

	}

	/**
	 *
	 * this method will handle a moderation on the view template
	 * Enter description here ...
	 */
	public function handleChange()
	{
		// TODO
	}

	/**
	 *
	 * get the mail content for the message to send
	 * returns array $messagecontent
	 */
	public function getMailContent($from, $fromaddress, $toaddress, $entry, $ticketcategory, $title, $text, $url, $editurl = '') {

		$serviceManager = ServiceUtil::getManager();
		$handler = new Zikula_Form_View($serviceManager, 'MUTicket');

		$messagecontent = array();
		$messagecontent['from'] = $from;
		$messagecontent['fromaddress'] = $fromaddress;
		/*$messagecontent['toname'] = 'Webmaster';*/
		$messagecontent['toaddress'] = $toaddress;
		$messagecontent['subject'] = $entry . $from . $ticketcategory;
		$messagecontent['body'] = $handler->__('Another entry was created by an user on '). '<h2>' . $from . '</h2>';
		$messagecontent['body'] .= $handler->__('Title') . '<br />' . $title . '<br /><br />';
		$messagecontent['body'] .= $handler->__('Text') . '<br />' . $text . '<br /><br />';
		$messagecontent['body'] .= $handler->__('Visit this ticket:') . '<br />';
		$messagecontent['body'] .= '<a href="' . $url . '">' . $url . '</a><br />';
		if ($editurl != '') {
			$messagecontent .= $handler->__('Moderate this entry:') .
                '<br />' . '<a href="' . $editurl . '">' . $editurl . '</a>';
		}
		$messagecontent['altbody'] = '';
		$messagecontent['html'] = true;

		return $messagecontent;
	}
	
	/**
	 * get the email address of the user that
	 * created parent ticket
	 * @parentid id of the parent ticket
	 * @returns $email string
	 */
	
	public function getMailAddressOfUser($parentid) {
		
		// get entity with id is parentid
		$entity = ModUtil::apiFunc('MUTicket', 'selection', 'getEntity', array('ot' => 'ticket', 'id' => $parentid));
		// get userid created the parent ticket
		$userid = $entity['createdUserId'];
		$email = UserUtil::getVar('email', $userid);
		
		return $email;
		
	}
}
