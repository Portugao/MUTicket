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
 * @version Generated by ModuleStudio 0.5.2 (http://modulestudio.de) at Thu Sep 15 21:40:56 CEST 2011.
 */

/**
 * Utility base class for view helper methods.
 */
class MUTicket_Util_Base_View extends Zikula_AbstractBase
{
    /**
     * Utility method for managing view templates.
     *
     * @param Zikula_View $view       Reference to view object.
     * @param string      $type       Current type (admin, user, ...).
     * @param string      $objectType Name of treated entity type.
     * @param string      $func       Current function (main, view, ...).
     * @param array       $args       Additional arguments.
     *
     * @return mixed Output.
     */
    public static function processTemplate($view, $type, $objectType, $func, $args = array())
    {
        // create the base template name
        $template = DataUtil::formatForOS($type . '/' . $objectType . '/' . $func);

        // check for template extension
        $templateExtension = self::determineExtension($view, $type, $objectType, $func, $args);

        // check whether a special template is used
        $tpl = FormUtil::getPassedValue('tpl', isset($args['tpl']) ? $args['tpl'] : '', 'GETPOST', FILTER_SANITIZE_STRING);
        /*if ($this->request->isPost() && $this->request->getPost()->has('tpl')) {
         $tpl = $this->request->getPost()->filter('tpl', $tpl, FILTER_SANITIZE_STRING);
         }
         elseif ($this->request->isGet() && $this->request->getGet()->has('tpl')) {
         $tpl = $this->request->getGet()->filter('tpl', $tpl, FILTER_SANITIZE_STRING);
         }*/
        $tpl = isset($args['tpl']) ? $args['tpl'] : $tpl;
        if (!empty($tpl) && $view->template_exists($template . '_' . DataUtil::formatForOS($tpl) . '.' . $templateExtension)) {
            $template .= '_' . DataUtil::formatForOS($tpl);
        }
        $template .= '.' . $templateExtension;

        // look whether we need output with or without the theme
        $raw = (bool)FormUtil::getPassedValue('raw', false, 'GETPOST', FILTER_VALIDATE_BOOLEAN);
        /*if ($this->request->isPost() && $this->request->getPost()->has('raw')) {
         $raw = (bool) $this->request->getPost()->filter('raw', false, FILTER_VALIDATE_BOOLEAN);
         }
         elseif ($this->request->isGet() && $this->request->getGet()->has('raw')) {
         $raw = (bool) $this->request->getGet()->filter('raw', false, FILTER_VALIDATE_BOOLEAN);
         }*/
        $raw = (isset($args['raw']) && is_bool($args['raw'])) ? $args['raw'] : $raw;
        if (!$raw && in_array($templateExtension, array('csv', 'rss', 'atom', 'xml', 'pdf', 'vcard', 'ical', 'json'))) {
            $raw = true;
        }

        if ($raw == true) {
            // standalone output
            if ($templateExtension == 'pdf') {
                return self::processPdf($view, $template);
            } else {
                $view->display($template);
            }
            System::shutDown();
        }

        // normal output
        return $view->fetch($template);
    }

    /**
     * Get extension of the currently treated template.
     *
     * @param Zikula_View $view       Reference to view object.
     * @param string      $type       Current type (admin, user, ...).
     * @param string      $objectType Name of treated entity type.
     * @param string      $func       Current function (main, view, ...).
     * @param array       $args       Additional arguments.
     *
     * @return array List of allowed template extensions.
     */
    protected static function determineExtension($view, $type, $objectType, $func, $args = array())
    {
        $templateExtension = 'tpl';
        if (!in_array($func, array('view', 'display'))) {
            return $templateExtension;
        }

        $extParams = self::availableExtensions($type, $objectType, $func, $args);
        foreach ($extParams as $extension) {
            $extensionCheck = (int)FormUtil::getPassedValue('use' . $extension . 'ext', 0, 'GET', FILTER_VALIDATE_INT);
            //$extensionCheck = (int)$this->request->getGet()->filter('use' . $extension . 'ext', 0, FILTER_VALIDATE_INT);
            if ($extensionCheck == 1) {
                $templateExtension = $extension;
                break;
            }
        }
        return $templateExtension;
    }

    /**
     * Get list of available template extensions.
     *
     * @param Zikula_View $view       Reference to view object.
     * @param string      $type       Current type (admin, user, ...).
     * @param string      $objectType Name of treated entity type.
     * @param string      $func       Current function (main, view, ...).
     * @param array       $args       Additional arguments.
     *
     * @return array List of allowed template extensions.
     */
    public static function availableExtensions($type, $objectType, $func, $args = array())
    {
        $extParams = array();
        if ($func == 'view') {
            if (SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_ADMIN)) {
                $extParams = array('csv', 'rss', 'atom', 'xml', 'json' /*, 'pdf'*/);
            } else {
                $extParams = array('rss', 'atom' /*, 'pdf'*/);
            }
        } elseif ($func == 'display') {
            if (SecurityUtil::checkPermission('MUTicket::', '::', ACCESS_ADMIN)) {
                $extParams = array('xml', 'json' /*, 'pdf'*/);
            }
        }
        return $extParams;
    }

    /**
     * Processes a template file using dompdf (LGPL).
     * To use this functionality:
     *    - download dompdf from the project page at http://www.digitaljunkies.ca/dompdf/
     *    - copy it to modules/MUTicket/lib/vendor/dompdf/ (see inclusion below)
     *    - override the corresponding template
     *
     * @param Zikula_View $view     Reference to view object.
     * @param string      $template Name of template to use.
     *
     * @return mixed Output.
     */
    protected static function processPdf(Zikula_View $view, $template)
    {
        // include dom pdf classes
        $pdfConfigFile = 'modules/MUTicket/lib/vendor/dompdf/dompdf_config.inc.php';
        if (!file_exists($pdfConfigFile)) {
            return false;
        }
        require_once($pdfConfigFile);

        // first the content, to set page vars
        $output = $view->fetch($template);

        // see http://codeigniter.com/forums/viewthread/69388/P15/#561214
        $output = utf8_decode($output);

        // then the surrounding
        $output = $view->fetch('include_pdfheader.tpl') . $output . '</body></html>';

        // create name of the pdf output file
        $fileTitle = MUTicket_Util_Controller::formatPermalink(System::getVar('sitename'))
            . '-'
            . MUTicket_Util_Controller::formatPermalink(PageUtil::getVar('title'))
            . '-' . date('Ymd') . '.pdf';

        //if ($_GET['dbg'] == 1) die($output);

        // instantiate pdf object
        $pdf = new DOMPDF();
        // define page properties
        $pdf->set_paper('A4');
        // load html input data
        $pdf->load_html($output);
        // create the actual pdf file
        $pdf->render();
        // stream output to browser
        $pdf->stream($fileTitle);

        // prevent additional output by shutting down the system
        System::shutDown();
        return true;
    }

    /**
     * Display a given file size in a readable format
     *
     * @param string  $size     File size in bytes.
     * @param boolean $nodesc   If set to true the description will not be appended.
     * @param boolean $onlydesc If set to true only the description will be returned.
     *
     * @return string File size in a readable form.
     */
    public static function getReadableFileSize($size, $nodesc = false, $onlydesc = false)
    {
        $dom = ZLanguage::getModuleDomain('MUTicket');
        $sizeDesc = __('Bytes', $dom);
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = __('KB', $dom);
        }
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = __('MB', $dom);
        }
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = __('GB', $dom);
        }
        $sizeDesc = '&nbsp;' . $sizeDesc;

        // format number
        $dec_point = ',';
        $thousands_separator = '.';
        if ($size - number_format($size, 0) >= 0.005) {
            $size = number_format($size, 2, $dec_point, $thousands_separator);
        } else {
            $size = number_format($size, 0, '', $thousands_separator);
        }

        // append size descriptor if desired
        if (!$nodesc) {
            $size .= $sizeDesc;
        }

        // return either only the description or the complete string
        $result = ($onlydesc) ? $sizeDesc : $size;
        return $result;
    }
}
