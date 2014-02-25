<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2052 $
 */
class ConfigFile {
    var $overwrite        =    true;
    var $booleanize        =    true;
    var $read_hidden     =    true;
    var $fix_newlines =    true;
    var $_config_path    = "";
    var $_config_data    = array();

    function __construct($config_path = NULL)
    {
        if (isset($config_path))
            $this->set_path($config_path);
    }

    function set_path($config_path)
    {
        if (!empty($config_path)) {
            if (!is_string($config_path) || !file_exists($config_path) || !is_dir($config_path)) {
                $this->_trigger_error_msg("Bad config file path '$config_path'");
                return;
            }
            if(substr($config_path, -1) != DIRECTORY_SEPARATOR) {
                $config_path .= DIRECTORY_SEPARATOR;
            }

            $this->_config_path = $config_path;
        }
    }

    function get($file_name, $section_name = NULL, $var_name = NULL)
    {
        if (empty($file_name)) {
            $this->_trigger_error_msg('Empty config file name');
            return;
        } else {
            $file_name = $this->_config_path . $file_name;
            if (!isset($this->_config_data[$file_name]))
                $this->load_file($file_name, false);
        }

        if (!empty($var_name)) {
            if (empty($section_name)) {
                return $this->_config_data[$file_name]["vars"][$var_name];
            } else {
                if(isset($this->_config_data[$file_name]["sections"][$section_name]["vars"][$var_name]))
                    return $this->_config_data[$file_name]["sections"][$section_name]["vars"][$var_name];
                else
                    return array();
            }
        } else {
            if (empty($section_name)) {
                return (array)$this->_config_data[$file_name]["vars"];
            } else {
                if(isset($this->_config_data[$file_name]["sections"][$section_name]["vars"]))
                    return (array)$this->_config_data[$file_name]["sections"][$section_name]["vars"];
                else
                    return array();
            }
        }
    }

    function &get_key($config_key)
    {
        list($file_name, $section_name, $var_name) = explode('/', $config_key, 3);
        $result = &$this->get($file_name, $section_name, $var_name);
        return $result;
    }

    function get_file_names()
    {
        return array_keys($this->_config_data);
    }

    function get_section_names($file_name)
    {
        $file_name = $this->_config_path . $file_name;
        if (!isset($this->_config_data[$file_name])) {
            $this->_trigger_error_msg("Unknown config file '$file_name'");
            return;
        }

        return array_keys($this->_config_data[$file_name]["sections"]);
    }

    function get_var_names($file_name, $section = NULL)
    {
        if (empty($file_name)) {
            $this->_trigger_error_msg('Empty config file name');
            return;
        } else if (!isset($this->_config_data[$file_name])) {
            $this->_trigger_error_msg("Unknown config file '$file_name'");
            return;
        }

        if (empty($section))
            return array_keys($this->_config_data[$file_name]["vars"]);
        else
            return array_keys($this->_config_data[$file_name]["sections"][$section]["vars"]);
    }

    function clear($file_name = NULL)
    {
        if ($file_name === NULL)
            $this->_config_data = array();
        else if (isset($this->_config_data[$file_name]))
            $this->_config_data[$file_name] = array();
    }

    function load_file($file_name, $prepend_path = true)
    {
    	global $G;
    	if(function_exists("pb_lang_split"))
    	$_tmp = pb_lang_split_recursive($G['setting']);
    	else $_tmp = $G['setting'];
        if ($prepend_path && $this->_config_path != "")
            $config_file = $this->_config_path . $file_name;
        else
            $config_file = $file_name;

        ini_set('track_errors', true);
        $fp = @fopen($config_file, "r");
        if (!is_resource($fp)) {
            $this->_trigger_error_msg("Could not open config file '$config_file'");
            return false;
        }

        $contents = ($size = filesize($config_file)) ? fread($fp, $size) : '';
        fclose($fp);
        $search = array('%site_name%', '%site_url%', '%company_name%', '%service_email%');
        $replace = array($_tmp['site_name'], $_tmp['site_url'], $_tmp['company_name'], $_tmp['service_email']);
        $contents = str_replace($search, $replace, $contents);
        $this->_config_data[$config_file] = $this->parse_contents($contents);
        return true;
    }

    /**
     * Store the contents of a file manually.
     *
     * @param string $config_file file name of the related contents
     * @param string $contents the file-contents to parse
     */
    function set_file_contents($config_file, $contents)
    {
        $this->_config_data[$config_file] = $this->parse_contents($contents);
        return true;
    }

    /**
     * parse the source of a configuration file manually.
     *
     * @param string $contents the file-contents to parse
     */
    function parse_contents($contents)
    {
        if($this->fix_newlines) {
            $contents = preg_replace('!\r\n?!', "\n", $contents);
        }

        $config_data = array();
        $config_data['sections'] = array();
        $config_data['vars'] = array();

        $vars =& $config_data['vars'];

        /* parse file line by line */
        preg_match_all('!^.*\r?\n?!m', $contents, $match);
        $lines = $match[0];
        for ($i=0, $count=count($lines); $i<$count; $i++) {
            $line = $lines[$i];
            if (empty($line)) continue;

            if ( substr($line, 0, 1) == '[' && preg_match('!^\[(.*?)\]!', $line, $match) ) {
                /* section found */
                if (substr($match[1], 0, 1) == '.') {
                    /* hidden section */
                    if ($this->read_hidden) {
                        $section_name = substr($match[1], 1);
                    } else {
                        /* break reference to $vars to ignore hidden section */
                        unset($vars);
                        $vars = array();
                        continue;
                    }
                } else {                    
                    $section_name = $match[1];
                }
                if (!isset($config_data['sections'][$section_name]))
                    $config_data['sections'][$section_name] = array('vars' => array());
                $vars =& $config_data['sections'][$section_name]['vars'];
                continue;
            }

            if (preg_match('/^\s*(\.?\w+)\s*=\s*(.*)/s', $line, $match)) {
                /* variable found */
                $var_name = rtrim($match[1]);
                if (strpos($match[2], '"""') === 0) {
                    /* handle multiline-value */
                    $lines[$i] = substr($match[2], 3);
                    $var_value = '';
                    while ($i<$count) {
                        if (($pos = strpos($lines[$i], '"""')) === false) {
                            $var_value .= $lines[$i++];
                        } else {
                            /* end of multiline-value */
                            $var_value .= substr($lines[$i], 0, $pos);
                            break;
                        }
                    }
                    $booleanize = false;

                } else {
                    /* handle simple value */
                    $var_value = preg_replace('/^([\'"])(.*)\1$/', '\2', rtrim($match[2]));
                    $booleanize = $this->booleanize;

                }
                /* replace internal phpb2b vars */
                $this->_set_config_var($vars, $var_name, $var_value, $booleanize);
            }
        }
        return $config_data;
    }

    function _set_config_var(&$container, $var_name, $var_value, $booleanize)
    {
        if (substr($var_name, 0, 1) == '.') {
            if (!$this->read_hidden)
                return;
            else
                $var_name = substr($var_name, 1);
        }

        if (!preg_match("/^[a-zA-Z_]\w*$/", $var_name)) {
            $this->_trigger_error_msg("Bad variable name '$var_name'");
            return;
        }

        if ($booleanize) {
            if (preg_match("/^(on|true|yes)$/i", $var_value))
                $var_value = true;
            else if (preg_match("/^(off|false|no)$/i", $var_value))
                $var_value = false;
        }
		//add pre underline 2011.3.28,ualink
        $var_name = "_".$var_name;
        if (!isset($container[$var_name]) || $this->overwrite)
            $container[$var_name] = $var_value;
        else {
            settype($container[$var_name], 'array');
            $container[$var_name][] = $var_value;
        }
    }

    function _trigger_error_msg($error_msg, $error_type = E_USER_WARNING)
    {
        trigger_error("Config_File error: $error_msg", $error_type);
    }
}
//:~
?>
