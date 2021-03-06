<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula_Form
 * @subpackage Template_Plugins
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Smarty function to wrap MUTicket_Form_View generated form controls with suitable form tags.
 *
 * @param array $params Parameters passed in the block tag.
 * @param string $content Content of the block.
 * @param Zikula_Form_View $view Reference to Zikula_Form_View object.
 *
 * @return string The rendered output.
 */
function smarty_block_muticketform($params, $content, $view)
{
    if ($content) {
        PageUtil::addVar('stylesheet', 'system/Theme/style/form/style.css');
        $encodingHtml = (array_key_exists('enctype', $params) ? " enctype=\"$params[enctype]\"" : '');
        $action = htmlspecialchars(System::getCurrentUri());
        $classString = '';
        if (isset($params['cssClass'])) {
            $classString = "class=\"$params[cssClass]\" ";
        }

        // we check if the entrypoint is part of the url
        $stripentrypoint = ModUtil::getVar('ZConfig', 'shorturlsstripentrypoint');

        if(strpos($action,"func=display")!==false) {
            $action = 'index.php?module=muticket&amp;type=user&amp;func=edit&amp;ot=ticket';
        }

        // we get request
        $request = new Zikula_Request_Http();
        // we check for id
        $id = $request->query->filter('id', 0, FILTER_SANITIZE_NUMBER_INT);
        $func = $request->query->filter('func', 'main', FILTER_SANITIZE_STRING);

        if (($id == 0 && $func == 'edit') || ($id > 0 && $func == 'display')) {
            if(strpos($action,"/ticket/")!==false) {
                if ($stripentrypoint == 1) {
                    $action = 'muticket/edit/ot/ticket';
                }
                elseif ($stripentrypoint == 0) {
                    $action = 'index.php/muticket/edit/ot/ticket';
                }
            }
        }

        $view->postRender();

        $formId = $view->getFormId();
        $out = "
        <form id=\"{$formId}\" {$classString}action=\"$action\" method=\"post\"{$encodingHtml}>
        $content
        <div>
        {$view->getStateHTML()}
        {$view->getStateDataHTML()}
        {$view->getIncludesHTML()}
        {$view->getCsrfTokenHtml()}
        <input type=\"hidden\" name=\"__formid\" id=\"form__id\" value=\"{$formId}\" />
        <input type=\"hidden\" name=\"FormEventTarget\" id=\"FormEventTarget\" value=\"\" />
        <input type=\"hidden\" name=\"FormEventArgument\" id=\"FormEventArgument\" value=\"\" />
        <script type=\"text/javascript\">
        <!--
        function FormDoPostBack(eventTarget, eventArgument)
        {
        var f = document.getElementById('{$formId}');
        if (!f.onsubmit || f.onsubmit())
        {
        f.FormEventTarget.value = eventTarget;
        f.FormEventArgument.value = eventArgument;
        f.submit();
    }
    }
    // -->
    </script>
    </div>
    </form>
    ";
        return $out;
    }
}