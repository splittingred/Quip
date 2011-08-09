<?php
/**
 * Quip
 *
 * Copyright 2010-11 by Shaun McCormick <shaun@modx.com>
 *
 * This file is part of Quip, a simple commenting component for MODx Revolution.
 *
 * Quip is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Quip is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Quip; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package quip
 */
/**
 * Base Hooks handling class
 *
 * @package quip
 */
class quipHooks {
    /**
     * @var array $errors A collection of all the processed errors so far.
     * @access public
     */
    public $errors = array();
    /**
     * @var array $hooks A collection of all the processed hooks so far.
     * @access public
     */
    public $hooks = array();
    /**
     * @var modX $modx A reference to the modX instance.
     * @access public
     */
    public $modx = null;
    /**
     * @var Quip $quip A reference to the Quip instance.
     * @access public
     */
    public $quip = null;
    /**
     * @var string If a hook redirects, it needs to set this var to use proper
     * order of execution on redirects/stores
     * @access public
     */
    public $redirectUrl = null;
    /**
     * @var array
     */
    public $fields = array();

    /**
     * The constructor for the quipHooks class
     *
     * @param Quip $quip A reference to the Quip class instance.
     * @param array $config Optional. An array of configuration parameters.
     */
    function __construct(Quip &$quip,array $config = array()) {
        $this->quip =& $quip;
        $this->modx =& $quip->modx;
        $this->config = array_merge(array(
        ),$config);
    }

    /**
     * Loads an array of hooks. If one fails, will not proceed.
     *
     * @access public
     * @param array $hooks The hooks to run.
     * @param array $fields The fields and values of the form
     * @param array $customProperties Any other custom properties to load into a custom hook
     * @return array An array of field name => value pairs.
     */
    public function loadMultiple($hooks,array $fields = array(),array $customProperties = array()) {
        if (empty($hooks)) return array();
        if (is_string($hooks)) $hooks = explode(',',$hooks);

        $this->hooks = array();
        $this->fields =& $fields;
        foreach ($hooks as $hook) {
            $hook = trim($hook);
            $success = $this->load($hook,$this->fields,$customProperties);
            if (!$success) return $this->hooks;
            /* dont proceed if hook fails */
        }
        return $this->hooks;
    }

    /**
     * Load a hook. Stores any errors for the hook to $this->errors.
     *
     * @access public
     * @param string $hook The name of the hook. May be a Snippet name.
     * @param array $fields The fields and values of the form.
     * @param array $customProperties Any other custom properties to load into a custom hook.
     * @return boolean True if hook was successful.
     */
    public function load($hook,array $fields = array(),array $customProperties = array()) {
        $success = false;
        if (!empty($fields)) $this->fields =& $fields;
        $this->hooks[] = $hook;

        $reserved = array('load','_process','__construct','getErrorMessage');
        if (method_exists($this,$hook) && !in_array($hook,$reserved)) {
            /* built-in hooks */
            $success = $this->$hook($this->fields);

        } else if ($snippet = $this->modx->getObject('modSnippet',array('name' => $hook))) {
            /* custom snippet hook */
            $properties = array_merge($this->quip->config,$customProperties);
            $properties['quip'] =& $this->quip;
            $properties['hook'] =& $this;
            $properties['fields'] = $this->fields;
            $properties['errors'] =& $this->errors;
            $success = $snippet->process($properties);

        } else {
            /* no hook found */
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Could not find hook "'.$hook.'".');
            $success = true;
        }

        if (is_array($success) && !empty($success)) {
            $this->errors = array_merge($this->errors,$success);
            $success = false;
        } else if ($success != true) {
            $this->errors[$hook] .= ' '.$success;
            $success = false;
        }
        return $success;
    }

    /**
     * Gets the error messages compiled into a single string.
     *
     * @access public
     * @param string $delim The delimiter between each message.
     * @return string The concatenated error message
     */
    public function getErrorMessage($delim = "\n") {
        return implode($delim,$this->errors);
    }

    /**
     * Adds an error to the stack.
     *
     * @access private
     * @param string $key The field to add the error to.
     * @param string $value The error message.
     * @return string The added error message with the error wrapper.
     */
    public function addError($key,$value) {
        $this->errors[$key] .= $value;
        return $this->errors[$key];
    }

    /**
     * Sets the value of a field.
     *
     * @param string $key The field name to set.
     * @param mixed $value The value to set to the field.
     * @return mixed The set value.
     */
    public function setValue($key,$value) {
        $this->fields[$key] = $value;
        return $this->fields[$key];
    }

    /**
     * Sets an associative array of field name and values.
     *
     * @param array $values A key/name pair of fields and values to set.
     */
    public function setValues(array $values = array()) {
        foreach ($values as $key => $value) {
            $this->setValue($key,$value);
        }
    }

    /**
     * Gets the value of a field.
     *
     * @param string $key The field name to get.
     * @return mixed The value of the key, or null if non-existent.
     */
    public function getValue($key) {
        if (array_key_exists($key,$this->fields)) {
            return $this->fields[$key];
        }
        return null;
    }

    /**
     * Gets an associative array of field name and values.
     *
     * @return array $values A key/name pair of fields and values.
     */
    public function getValues() {
        return $this->fields;
    }


    /**
     * Used for debugging to test preHooks.
     * @return bool Always false
     */
    public function testFail() {
        $this->addError('comment','Fail!');
        return false;
    }

    /**
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }
    
    /**
     * Redirect to a specified URL.
     *
     * Properties needed:
     * - redirectTo - the ID of the Resource to redirect to.
     *
     * @param array $fields An array of cleaned POST fields
     * @return boolean False if unsuccessful.
     */
    public function redirect(array $fields = array()) {
        if (empty($this->quip->config['redirectTo'])) return false;
        $redirectParams = !empty($this->quip->config['redirectParams']) ? $this->quip->config['redirectParams'] : '';
        if (!empty($redirectParams)) {
            $prefix = $this->modx->getOption('placeholderPrefix',$this->quip->config,'fi.');
            $this->modx->setPlaceholders($fields,$prefix);
            $this->modx->parser->processElementTags('',$redirectParams,true,true);
            $redirectParams = $this->modx->fromJSON($redirectParams);
            if (empty($redirectParams)) $redirectParams = '';
        }
        $url = $this->modx->makeUrl($this->quip->config['redirectTo'],'',$redirectParams,'abs');
        $this->setRedirectUrl($url);
        return true;
    }

    /**
     * Processes string and sets placeholders
     *
     * @param string $str The string to process
     * @param array $placeholders An array of placeholders to replace with values
     * @return string The parsed string
     */
    public function _process($str,array $placeholders = array()) {
        foreach ($placeholders as $k => $v) {
            $str = str_replace('[[+'.$k.']]',$v,$str);
        }
        return $str;
    }

    /**
     * Set a URL to redirect to after all hooks run successfully.
     *
     * @param string $url The URL to redirect to after all hooks execute
     */
    public function setRedirectUrl($url) {
        $this->redirectUrl = $url;
    }

    /**
     * Math field hook for anti-spam math input field.
     *
     * @access public
     * @param array $fields An array of cleaned POST fields
     * @return boolean True if email was successfully sent.
     */
    public function math(array $fields = array()) {
        $op1Field = $this->modx->getOption('mathOp1Field',$this->quip->config,'op1');
        if (empty($fields[$op1Field])) { $this->errors[$op1Field] = $this->modx->lexicon('quip.math_field_nf',array('field' => $op1Field)); return false; }
        $op2Field = $this->modx->getOption('mathOp2Field',$this->quip->config,'op2');
        if (empty($fields[$op2Field])) { $this->errors[$op2Field] = $this->modx->lexicon('quip.math_field_nf',array('field' => $op2Field)); return false; }
        $operatorField = $this->modx->getOption('mathOperatorField',$this->quip->config,'operator');
        if (empty($fields[$operatorField])) { $this->errors[$operatorField] = $this->modx->lexicon('quip.math_field_nf',array('field' => $operatorField)); return false; }
        $mathField = $this->modx->getOption('mathField',$this->quip->config,'math');
        if (empty($fields[$mathField])) { $this->errors[$mathField] = $this->modx->lexicon('quip.math_field_nf',array('field' => $mathField)); return false; }

        $answer = false;
        $op1 = (int)$fields[$op1Field];
        $op2 = (int)$fields[$op2Field];
        switch ($fields[$operatorField]) {
            case '+': $answer = $op1 + $op2; break;
            case '-': $answer = $op1 - $op2; break;
            case '*': $answer = $op1 * $op2; break;
        }
        $guess = (int)$fields[$mathField];
        $passed = (boolean)($guess == $answer);
        if (!$passed) {
            $this->errors[$mathField] = $this->modx->lexicon('quip.math_incorrect');
        }
        return $passed;
    }
}