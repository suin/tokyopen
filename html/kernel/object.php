<?php
// $Id: object.php,v 1.1 2007/05/15 02:34:37 minahito Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */

/**#@+
 * Xoops object datatype
 *
 **/
define('XOBJ_DTYPE_STRING', 1);
define('XOBJ_DTYPE_TXTBOX', 1);
define('XOBJ_DTYPE_TEXT', 2);
define('XOBJ_DTYPE_TXTAREA', 2);
define('XOBJ_DTYPE_INT', 3);
define('XOBJ_DTYPE_URL', 4);
define('XOBJ_DTYPE_EMAIL', 5);
define('XOBJ_DTYPE_ARRAY', 6);
define('XOBJ_DTYPE_OTHER', 7);
define('XOBJ_DTYPE_SOURCE', 8);
define('XOBJ_DTYPE_STIME', 9);
define('XOBJ_DTYPE_MTIME', 10);
define('XOBJ_DTYPE_LTIME', 11);
define('XOBJ_DTYPE_FLOAT', 12);
define('XOBJ_DTYPE_BOOL', 13);
/**#@-*/

/**
 * Interface for all objects in the Xoops kernel.
 */
class AbstractXoopsObject
{
	function setNew()
	{
	}
	
	function unsetNew()
	{
	}

	/**
	 * @return bool
	 */	
	function isNew()
	{
	}
	
	function initVar($key, $data_type, $default, $required, $size)
	{
	}
	
	/**
	 * You should use this method to initilize object's properties.
	 * This method may not trigger setDirty().
	 * @param $values array
	 */	
	function assignVars($values)
	{
	}
	
	/**
	 * You should use this method to change object's properties.
	 * This method may trigger setDirty().
	 */
	function set($key, $value)
	{
	}
	
	function get($key)
	{
	}
	
	/**
	 * Return html string for template.
	 * You can call get() method to get pure value.
	 */
	function getShow($key)
	{
	}
}


/**
 * Base class for all objects in the Xoops kernel (and beyond)
 *
 * @author Kazumi Ono (AKA onokazu)
 * @copyright copyright &copy; 2000 XOOPS.org
 * @package kernel
 **/
class XoopsObject extends AbstractXoopsObject
{

    /**
     * holds all variables(properties) of an object
     *
     * @var array
     * @access protected
     **/
    var $vars = array();

    /**
    * variables cleaned for store in DB
    *
    * @var array
    * @access protected
    */
    var $cleanVars = array();

    /**
    * is it a newly created object?
    *
    * @var bool
    * @access private
    */
    var $_isNew = false;

    /**
    * has any of the values been modified?
    *
    * @var bool
    * @access private
    */
    var $_isDirty = false;

    /**
    * errors
    *
    * @var array
    * @access private
    */
    var $_errors = array();

    /**
    * additional filters registered dynamically by a child class object
    *
    * @access private
    */
    var $_filters = array();

    /**
    * constructor
    *
    * normally, this is called from child classes only
    * @access public
    */
    function XoopsObject()
    {
    }

    /**#@+
    * used for new/clone objects
    *
    * @access public
    */
    function setNew()
    {
        $this->_isNew = true;
    }
    function unsetNew()
    {
        $this->_isNew = false;
    }
    function isNew()
    {
        return $this->_isNew;
    }
    /**#@-*/

    /**#@+
    * mark modified objects as dirty
    *
    * used for modified objects only
    * @access public
    */
    function setDirty()
    {
        $this->_isDirty = true;
    }
    function unsetDirty()
    {
        $this->_isDirty = false;
    }
    function isDirty()
    {
        return $this->_isDirty;
    }
    /**#@-*/

    /**
    * initialize variables for the object
    *
    * @access public
    * @param string $key
    * @param int $data_type  set to one of XOBJ_DTYPE_XXX constants (set to XOBJ_DTYPE_OTHER if no data type ckecking nor text sanitizing is required)
    * @param mixed
    * @param bool $required  require html form input?
    * @param int $maxlength  for XOBJ_DTYPE_TXTBOX type only
    * @param string $option  does this data have any select options?
    */
    function initVar($key, $data_type, $value = null, $required = false, $maxlength = null, $options = '')
    {
        $this->vars[$key] = array('value' => $value, 'required' => $required, 'data_type' => $data_type, 'maxlength' => $maxlength, 'changed' => false, 'options' => $options);
    }

    /**
    * assign a value to a variable
    *
    * @access public
    * @param string $key name of the variable to assign
    * @param mixed $value value to assign
    */
    function assignVar($key, $value)
    {
        if (isset($value) && isset($this->vars[$key])) {
            $this->vars[$key]['value'] =& $value;
        }
    }

    /**
    * assign values to multiple variables in a batch
    *
    * @access private
    * @param array $var_array associative array of values to assign
    */
    function assignVars($var_arr)
    {
        foreach ($var_arr as $key => $value) {
            $this->assignVar($key, $value);
        }
    }

    /**
    * assign a value to a variable
    *
    * @access public
    * @param string $key name of the variable to assign
    * @param mixed $value value to assign
    * @param bool $not_gpc
    */
    function setVar($key, $value, $not_gpc = false)
    {
        if (!empty($key) && isset($value) && isset($this->vars[$key])) {
            $this->vars[$key]['value'] =& $value;
            $this->vars[$key]['not_gpc'] = $not_gpc;
            $this->vars[$key]['changed'] = true;
            $this->setDirty();
        }
    }

    /**
    * assign values to multiple variables in a batch
    *
    * @access private
    * @param array $var_arr associative array of values to assign
    * @param bool $not_gpc
    */
    function setVars($var_arr, $not_gpc = false)
    {
        foreach ($var_arr as $key => $value) {
            $this->setVar($key, $value, $not_gpc);
        }
    }

    /**
    * Assign values to multiple variables in a batch
    *
    * Meant for a CGI contenxt:
    * - prefixed CGI args are considered save
    * - avoids polluting of namespace with CGI args
    *
    * @access private
    * @param array $var_arr associative array of values to assign
    * @param string $pref prefix (only keys starting with the prefix will be set)
    */
    function setFormVars($var_arr=null, $pref='xo_', $not_gpc=false) {
        $len = strlen($pref);
        foreach ($var_arr as $key => $value) {
            if ($pref == substr($key,0,$len)) {
                $this->setVar(substr($key,$len), $value, $not_gpc);
            }
        }
    }


    /**
    * returns all variables for the object
    *
    * @access public
    * @return array associative array of key->value pairs
    */
    function &getVars()
    {
        return $this->vars;
    }

    /**
    * returns a specific variable for the object in a proper format
    *
    * @access public
    * @param string $key key of the object's variable to be returned
    * @param string $format format to use for the output
    * @return mixed formatted value of the variable
    */
    function &getVar($key, $format = 's')
    {
        $ret = $this->vars[$key]['value'];
        switch ($this->vars[$key]['data_type']) {

        case XOBJ_DTYPE_TXTBOX:
            switch (strtolower($format)) {
            case 's':
            case 'show':
            case 'e':
            case 'edit':
                $ts =& MyTextSanitizer::getInstance();
                $ret = $ts->htmlSpecialChars($ret);
                break 1;
            case 'p':
            case 'preview':
            case 'f':
            case 'formpreview':
                $ts =& MyTextSanitizer::getInstance();
                $ret = $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));
                break 1;
            case 'n':
            case 'none':
            default:
                break 1;
            }
            break;
        case XOBJ_DTYPE_TXTAREA:
            switch (strtolower($format)) {
            case 's':
            case 'show':
                $ts =& MyTextSanitizer::getInstance();
                $html = !empty($this->vars['dohtml']['value']) ? 1 : 0;
                $xcode = (!isset($this->vars['doxcode']['value']) || $this->vars['doxcode']['value'] == 1) ? 1 : 0;
                $smiley = (!isset($this->vars['dosmiley']['value']) || $this->vars['dosmiley']['value'] == 1) ? 1 : 0;
                $image = (!isset($this->vars['doimage']['value']) || $this->vars['doimage']['value'] == 1) ? 1 : 0;
                $br = (!isset($this->vars['dobr']['value']) || $this->vars['dobr']['value'] == 1) ? 1 : 0;
                $ret = $ts->displayTarea($ret, $html, $smiley, $xcode, $image, $br);
                break 1;
            case 'e':
            case 'edit':
                $ret = htmlspecialchars($ret, ENT_QUOTES);
                break 1;
            case 'p':
            case 'preview':
                $ts =& MyTextSanitizer::getInstance();
                $html = !empty($this->vars['dohtml']['value']) ? 1 : 0;
                $xcode = (!isset($this->vars['doxcode']['value']) || $this->vars['doxcode']['value'] == 1) ? 1 : 0;
                $smiley = (!isset($this->vars['dosmiley']['value']) || $this->vars['dosmiley']['value'] == 1) ? 1 : 0;
                $image = (!isset($this->vars['doimage']['value']) || $this->vars['doimage']['value'] == 1) ? 1 : 0;
                $br = (!isset($this->vars['dobr']['value']) || $this->vars['dobr']['value'] == 1) ? 1 : 0;
                $ret = $ts->previewTarea($ret, $html, $smiley, $xcode, $image, $br);
                break 1;
            case 'f':
            case 'formpreview':
                $ts =& MyTextSanitizer::getInstance();
                $ret = htmlspecialchars($ts->stripSlashesGPC($ret), ENT_QUOTES);
                break 1;
            case 'n':
            case 'none':
            default:
                break 1;
            }
            break;
        case XOBJ_DTYPE_ARRAY:
            $ret = unserialize($ret);
            break;
        case XOBJ_DTYPE_SOURCE:
            switch (strtolower($format)) {
            case 's':
            case 'show':
                break 1;
            case 'e':
            case 'edit':
                $ret = htmlspecialchars($ret, ENT_QUOTES);
                break 1;
            case 'p':
            case 'preview':
                $ts =& MyTextSanitizer::getInstance();
                $ret = $ts->stripSlashesGPC($ret);
                break 1;
            case 'f':
            case 'formpreview':
                $ts =& MyTextSanitizer::getInstance();
                $ret = htmlspecialchars($ts->stripSlashesGPC($ret), ENT_QUOTES);
                break 1;
            case 'n':
            case 'none':
            default:
                break 1;
            }
            break;
        default:
            if ($this->vars[$key]['options'] != '' && $ret != '') {
                switch (strtolower($format)) {
                case 's':
                case 'show':
                    $selected = explode('|', $ret);
                    $options = explode('|', $this->vars[$key]['options']);
                    $i = 1;
                    $ret = array();
                    foreach ($options as $op) {
                        if (in_array($i, $selected)) {
                            $ret[] = $op;
                        }
                        $i++;
                    }
                    $ret = implode(', ', $ret);
                case 'e':
                case 'edit':
                    $ret = explode('|', $ret);
                    break 1;
                default:
                    break 1;
                }

            }
            break;
        }
        return $ret;
    }

	function getShow($key)
	{
		return $this->getVar($key, 's');
	}
	
	/**
	 * Sets $value to $key property. This method calls setVar(), but make
	 * not_gpc true for the compatibility with XoopsSimpleObject.
	 * @param string $key
	 * @param mixed $value
	 */
	function set($key, $value)
	{
		$this->setVar($key, $value, true);
	}

	function get($key)
	{
		return $this->vars[$key]['value'];
	}

    /**
     * Return value as raw.
     * @deprecated
     */
    function getProperty($key)
    {
		return $this->vars[$key]['value'];
	}

	/**
     * @deprecated
	 */
    function getProperties()
    {
		$ret=array();
		foreach(array_keys($this->vars) as $key) {
			$ret[$key]=$this->vars[$key]['value'];
		}
		return $ret;
	}

    /**
     * clean values of all variables of the object for storage.
     * also add slashes whereever needed
     *
     * @return bool true if successful
     * @access public
     */
    function cleanVars()
    {
        $ts =& MyTextSanitizer::getInstance();
        foreach ($this->vars as $k => $v) {
            $cleanv = $v['value'];
            if (!$v['changed']) {
            } else {
                $cleanv = is_string($cleanv) ? trim($cleanv) : $cleanv;
                switch ($v['data_type']) {
                case XOBJ_DTYPE_TXTBOX:
                    if ($v['required'] && $cleanv != '0' && $cleanv == '') {
                        $this->setErrors("$k is required.");
                        continue;
                    }
                    if (isset($v['maxlength']) && strlen($cleanv) > intval($v['maxlength'])) {
                        $this->setErrors("$k must be shorter than ".intval($v['maxlength'])." characters.");
                        continue;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($ts->censorString($cleanv));
                    } else {
                        $cleanv = $ts->censorString($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_TXTAREA:
                    if ($v['required'] && $cleanv != '0' && $cleanv == '') {
                        $this->setErrors("$k is required.");
                        continue;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($ts->censorString($cleanv));
                    } else {
                        $cleanv = $ts->censorString($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_SOURCE:
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($cleanv);
                    } else {
                        $cleanv = $cleanv;
                    }
                    break;

                case XOBJ_DTYPE_INT:
                    $cleanv = intval($cleanv);
                    break;

                case XOBJ_DTYPE_FLOAT:
                    $cleanv = floatval($cleanv);
                    break;

                case XOBJ_DTYPE_BOOL:
                    $cleanv = $cleanv ? 1 : 0;
                    break;

                case XOBJ_DTYPE_EMAIL:
                    if ($v['required'] && $cleanv == '') {
                        $this->setErrors("$k is required.");
                        continue;
                    }
                    if ($cleanv != '' && !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$cleanv)) {
                        $this->setErrors("Invalid Email");
                        continue;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv = $ts->stripSlashesGPC($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_URL:
                    if ($v['required'] && $cleanv == '') {
                        $this->setErrors("$k is required.");
                        continue;
                    }
                    if ($cleanv != '' && !preg_match("/^http[s]*:\/\//i", $cleanv)) {
                        $cleanv = 'http://' . $cleanv;
                    }
                    if (!$v['not_gpc']) {
                        $cleanv =& $ts->stripSlashesGPC($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_ARRAY:
                    $cleanv = serialize($cleanv);
                    break;
                case XOBJ_DTYPE_STIME:
                case XOBJ_DTYPE_MTIME:
                case XOBJ_DTYPE_LTIME:
                    $cleanv = !is_string($cleanv) ? intval($cleanv) : strtotime($cleanv);
                    break;
                default:
                    break;
                }
            }
            $this->cleanVars[$k] =& $cleanv;
            unset($cleanv);
        }
        if (count($this->_errors) > 0) {
            return false;
        }
        $this->unsetDirty();
        return true;
    }

    /**
     * dynamically register additional filter for the object
     *
     * @param string $filtername name of the filter
     * @access public
     */
    function registerFilter($filtername)
    {
        $this->_filters[] = $filtername;
    }

    /**
     * load all additional filters that have been registered to the object
     *
     * @access private
     */
    function _loadFilters()
    {
        //include_once XOOPS_ROOT_PATH.'/class/filters/filter.php';
        //foreach ($this->_filters as $f) {
        //    include_once XOOPS_ROOT_PATH.'/class/filters/'.strtolower($f).'php';
        //}
    }

    /**
     * create a clone(copy) of the current object
     *
     * @access public
     * @return object clone
     */
    function &xoopsClone()
    {
        $class = get_class($this);
        $clone = new $class();
        foreach ($this->vars as $k => $v) {
            $clone->assignVar($k, $v['value']);
        }
        // need this to notify the handler class that this is a newly created object
        $clone->setNew();
        return $clone;
    }

    /**
     * add an error
     *
     * @param string $value error to add
     * @access public
     */
    function setErrors($err_str)
    {
        $this->_errors[] = trim($err_str);
    }

    /**
     * return the errors for this object as an array
     *
     * @return array an array of errors
     * @access public
     */
    function getErrors()
    {
        return $this->_errors;
    }

    /**
     * return the errors for this object as html
     *
     * @return string html listing the errors
     * @access public
     */
    function getHtmlErrors()
    {
        $ret = '<h4>Errors</h4>';
        if (!empty($this->_errors)) {
            foreach ($this->_errors as $error) {
                $ret .= $error.'<br />';
            }
        } else {
            $ret .= 'None<br />';
        }
        return $ret;
    }
}

/**
* XOOPS object handler class.
* This class is an abstract class of handler classes that are responsible for providing
* data access mechanisms to the data source of its corresponsing data objects
* @package kernel
* @abstract
*
* @author  Kazumi Ono <onokazu@xoops.org>
* @copyright copyright &copy; 2000 The XOOPS Project
*/
class XoopsObjectHandler
{

    /**
     * holds referenced to {@link XoopsDatabase} class object
     *
     * @var object
     * @see XoopsDatabase
     * @access protected
     */
    var $db;

    //
    /**
     * called from child classes only
     *
     * @param object $db reference to the {@link XoopsDatabase} object
     * @access protected
     */
    function XoopsObjectHandler(&$db)
    {
        $this->db =& $db;
    }

    /**
     * creates a new object
     *
     * @abstract
     */
    function &create()
    {
    }

    /**
     * gets a value object
     *
     * @param int $int_id
     * @abstract
     */
    function &get($int_id)
    {
    }

    /**
     * insert/update object
     *
     * @param object $object
     * @abstract
     */
    function insert(&$object)
    {
    }

    /**
     * delete obejct from database
     *
     * @param object $object
     * @abstract
     */
    function delete(&$object)
    {
    }

}
?>