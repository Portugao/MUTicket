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
 * Validator class for encapsulating common entity validation methods.
 *
 * This is the base validation class with general checks.
 */
abstract class MUTicket_Base_Validator extends Zikula_AbstractBase
{
    /**
     * @var Zikula_EntityAccess The entity instance which is treated by this validator.
     */
    protected $entity = null;

    /**
     * Constructor.
     *
     * @param Zikula_EntityAccess $entity The entity to be validated.
     */
    public function __construct(Zikula_EntityAccess $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Checks if field value is a valid boolean.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isValidBoolean($fieldName)
    {
        return (is_bool($this->entity[$fieldName]));
    }

    /**
     * Checks if field value is a valid number.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isValidNumber($fieldName)
    {
        return (is_numeric($this->entity[$fieldName]));
    }

    /**
     * Checks if field value is a valid integer.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isValidInteger($fieldName)
    {
        $val = $this->entity[$fieldName];
        return ($val == intval($val));
    }

    /**
     * Checks if integer field value is not lower than a given value.
     *
     * @param string $fieldName The name of the property to be checked
     * @param int    $value     The maximum allowed value
     * @return boolean result of this check
     */
    public function isIntegerNotLowerThan($fieldName, $value)
    {
        return ($this->isValidInteger($fieldName) && $this->entity[$fieldName] >= $value);
    }

    /**
     * Checks if integer field value is not higher than a given value.
     *
     * @param string $fieldName The name of the property to be checked
     * @param int    $value     The maximum allowed value
     * @return boolean result of this check
     */
    public function isIntegerNotHigherThan($fieldName, $value)
    {
        return ($this->isValidInteger($fieldName) && $this->entity[$fieldName] <= $value);
    }

    /**
     * Checks if field value is a valid user id.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isValidUser($fieldName)
    {
        if (!$this->isValidInteger($fieldName)) {
            return false;
        }
        $uname = UserUtil::getVar('uname', $this->entity[$fieldName]);
        return (!is_null($uname) && !empty($uname));
    }

    /**
     * Checks if numeric field value has a value other than 0.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isNumberNotEmpty($fieldName)
    {
        return $this->entity[$fieldName] != 0;
    }

    /**
     * Checks if string field value has a value other than ''.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isStringNotEmpty($fieldName)
    {
        return $this->entity[$fieldName] != '';
    }

    /**
     * Checks if numeric field value has a given minimum field length
     *
     * @param string $fieldName The name of the property to be checked
     * @param int    $length    The minimum length
     * @return boolean result of this check
     */
    public function isNumberNotShorterThan($fieldName, $length)
    {
        $minValue = pow(10, $length - 1);
        return ($this->isValidNumber($fieldName) && $this->entity[$fieldName] > $minValue);
    }

    /**
     * Checks if numeric field value does fit into given field length.
     *
     * @param string $fieldName The name of the property to be checked
     * @param int    $length    The maximum allowed length
     * @return boolean result of this check
     */
    public function isNumberNotLongerThan($fieldName, $length)
    {
        $maxValue = pow(10, $length);
        return ($this->isValidNumber($fieldName) && $this->entity[$fieldName] < $maxValue);
    }

    /**
     * Checks if string field value has a given minimum field length.
     *
     * @param string $fieldName The name of the property to be checked
     * @param int    $length    The minimum length
     * @return boolean result of this check
     */
    public function isStringNotShorterThan($fieldName, $length)
    {
        return (strlen($this->entity[$fieldName]) >= $length);
    }

    /**
     * Checks if string field value does fit into given field length.
     *
     * @param string $fieldName The name of the property to be checked
     * @param int    $length    The maximum allowed length
     * @return boolean result of this check
     */
    public function isStringNotLongerThan($fieldName, $length)
    {
        return (strlen($this->entity[$fieldName]) <= $length);
    }

    /**
     * Checks if string field value does conform to given fixed field length.
     *
     * @param string $fieldName The name of the property to be checked
     * @param int    $length    The fixed length
     * @return boolean result of this check
     */
    public function isStringWithFixedLength($fieldName, $length)
    {
        return (strlen($this->entity[$fieldName]) == $length);
    }

    /**
     * Checks if string field value does not contain a given string.
     *
     * @param string  $fieldName     The name of the property to be checked
     * @param string  $keyword       The char or string to search for
     * @param boolean $caseSensitive Whether the search should be case sensitive or not (default false)
     * @return boolean result of this check
     */
    public function isStringNotContaining($fieldName, $keyword, $caseSensitive = false)
    {
        if ($caseSensitive === true) {
            return (strstr($this->entity[$fieldName], $keyword) !== false);
        }
        return (stristr($this->entity[$fieldName], $keyword) !== false);
    }

    /**
     * Checks if string field value conforms to a given regular expression.
     *
     * @param string  $fieldName     The name of the property to be checked
     * @param string  $expression    Regular expression string
     * @return boolean result of this check
     */
    public function isValidRegExp($fieldName, $expression)
    {
        return preg_match($expression, $this->entity[$fieldName]);
    }

    /**
     * Checks if string field value is a valid language code.
     *
     * @param string  $fieldName     The name of the property to be checked
     * @param boolean $onlyInstalled Whether to accept only installed languages (default false)
     * @return boolean result of this check
     */
    public function isValidLanguage($fieldName, $onlyInstalled = false)
    {
        $languageMap = ZLanguage::languageMap();
        $result = in_array($this->entity[$fieldName], array_keys($languageMap));
        if (!$result || !$onlyInstalled) {
            return $result;
        }
        $available = ZLanguage::getInstalledLanguages();
        return in_array($this->entity[$fieldName], $available);
    }

    /**
     * Checks if string field value is a valid country code.
     *
     * @param string  $fieldName     The name of the property to be checked
     * @return boolean result of this check
     */
    public function isValidCountry($fieldName)
    {
        $countryMap = ZLanguage::countryMap();
        return in_array($this->entity[$fieldName], array_keys($countryMap));
    }

    /**
     * Checks if string field value is a valid html colour.
     *
     * @param string  $fieldName     The name of the property to be checked
     * @return boolean result of this check
     */
    public function isValidHtmlColour($fieldName)
    {
        $regex = '/^#?(([a-fA-F0-9]{3}){1,2})$/';
        return preg_match($regex, $this->entity[$fieldName]);
    }

    /**
     * Checks if field value is a valid email address.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isValidEmail($fieldName)
    {
        return filter_var($this->entity[$fieldName], FILTER_VALIDATE_EMAIL);
    }

    /**
     * Checks if field value is a valid url.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isValidUrl($fieldName)
    {
        return filter_var($this->entity[$fieldName], FILTER_VALIDATE_URL);
    }

    /**
     * Checks if field value is a valid DateTime instance.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isValidDateTime($fieldName)
    {
        return ($this->entity[$fieldName] instanceof DateTime);
    }

    /**
     * Checks if field value has a value in the past.
     *
     * @param string $fieldName The name of the property to be checked
     * @param string $format    The date format used for comparison
     * @return boolean result of this check
     */
    protected function isDateTimeValueInPast($fieldName, $format)
    {
        return ($this->isValidDateTime($fieldName) && $this->entity[$fieldName]->format($format) < date($format));
    }

    /**
     * Checks if field value has a value in the future.
     *
     * @param string $fieldName The name of the property to be checked
     * @param string $format    The date format used for comparison
     * @return boolean result of this check
     */
    protected function isDateTimeValueInFuture($fieldName, $format)
    {
        return ($this->isValidDateTime($fieldName) && $this->entity[$fieldName]->format($format) > date($format));
    }

    /**
     * Checks if field value is a datetime in the past.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isDateTimeInPast($fieldName)
    {
        return $this->isDateTimeValueInPast($fieldName, 'U');
    }

    /**
     * Checks if field value is a datetime in the future.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isDateTimeInFuture($fieldName)
    {
        return $this->isDateTimeValueInFuture($fieldName, 'U');
    }

    /**
     * Checks if field value is a date in the past.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isDateInPast($fieldName)
    {
        return $this->isDateTimeValueInPast($fieldName, 'Ymd');
    }

    /**
     * Checks if field value is a date in the future.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isDateInFuture($fieldName)
    {
        return $this->isDateTimeValueInFuture($fieldName, 'Ymd');
    }

    /**
     * Checks if field value is a time in the past.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isTimeInPast($fieldName)
    {
        return $this->isDateTimeValueInPast($fieldName, 'His');
    }

    /**
     * Checks if field value is a time in the future.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check
     */
    public function isTimeInFuture($fieldName)
    {
        return $this->isDateTimeValueInFuture($fieldName, 'His');
    }
}
