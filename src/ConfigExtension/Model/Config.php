<?php
namespace ConfigExtension\Model;

/**
 * Retrieves configurations from ini files
 * Date: 08/02/11
 * @author alinares
 */

class Config
{
    public $data = null;

    public $replacements;

    /**
     * Loads a configuration file
     *
     * @param string $file_name string Name of the configuration file to load (w/o
     * extensions, or dirs).
     * @param $rep array
     *
     * @return \ConfigExtension\Model\Config
     */
    public function __construct($file_name, $rep = array())
    {
        $this->replacements = $rep;
        $this->data = $this->load($file_name);
    }

    /**
     * Retrieves a configuration entry
     *
     * @param  $key      string
     * @param  $default  mixed
     *
     * @return string
     */
    public function get($key, $default = null)
    {
        if (isset($this->data[$key]))
        {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * Sets a configuration setting into memory

     * @param  $key string
     * @param  $value string
     * @return mixed
     */
    public function set($key, $value)
    {
        if (is_array($this->data))
        {
            return $this->data[$key] = $value;
        }

        return null;
    }

    /**
     * @param  $key string
     * @return bool
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Reads a ini file and returns an array
     *
     * @static
     * @param string $file_name
     *
     * @return array
     */
    public function load($file_name)
    {
        $parsed_ini_file = array();
        $parsed = parse_ini_file($file_name, true);
        if ($parsed === false)
        {
            throw new \Exception('Error opening .ini file!');
        }

        foreach ($parsed as $section => $arr)
        {
            if (is_array($arr))
            {
                foreach($arr as $key => $val)
                {
                    $val = $this->replaceVariables($val);
                    $parsed_ini_file["$section.$key"] = $val;
                }
            }
            else
            {
                $val = $this->replaceVariables($arr);
                $parsed_ini_file["$section"] = $val;
            }
        }
        return $parsed_ini_file;
    }

    public function replaceVariables($val)
    {
        if (preg_match('/\%([\w\.]+)\%/', $val, $matches))
        {
            $var = $matches[1];
            if ($replacement = isset($this->replacements[$var]) ?: null)
            {
                $val = str_replace("%$var%", $replacement, $val);
            }
            return $val;
        }
        return $val;
    }

    /**
     * Groups conf keys by prefix
     *
     * @param string $name
     * @param bool $remove_prepend
     *
     * @return array
     */
    public function getSection($name, $remove_prepend = false)
    {
        $grouped = array();
        foreach($this->data as $key => $val)
        {
            if (preg_match("/^$name\\./", $key))
            {
                if ($remove_prepend)
                {
                    $key = preg_replace("/^$name\\./", '', $key);
                }

                $grouped[$key] = $val;
            }
        }
        return $grouped;
    }

    /**
     * Adds to an array the configs found in a section
     *
     * @param \ArrayAccess $array
     * @param string $section
     */
    public function registerSection($array, $section)
    {
        $confs = $this->getSection($section);
        foreach($confs as $key => $val)
        {
            $array[$key] = $val;
        }
    }
}
