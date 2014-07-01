<?php

/**
 * Class Target - Instance of a Target URL for parsing
 */
class Target
{
    /**
     * @var string URL of target for parsing
     */
    private $_url = false;

    /**
     * @var int Size of target in bytes
     */
    private $_size = 0;

    /**
     * @var array Errors from parser
     */
    private $_errors = array();

    /**
     * Construct Parser Object for URL
     *
     * @param $url
     */
    public function __construct($url = false)
    {
        $this->setUrl($url);
    }

    /**
     * Sets the target URL
     *
     * @param $url
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }

    /**
     * Returns the URL of the target, with option of urlencoding it first
     *
     * @param bool $encode Flag to determine if URL should be encoded first
     *
     * @return string
     */
    public function getUrl($encode = false)
    {
        return $encode ? urlencode($this->_url) : $this->_url;
    }

    /**
     * @param array $errors
     *
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->_errors = $errors;

        return $this;
    }

    /**
     * Add Error to Errors
     *
     * @param string $errMsg
     *
     * @return $this
     */
    public function addError($errMsg)
    {
        $this->_errors[] = $errMsg;

        return $this;
    }

    /**
     * Check if Target has errors
     *
     * @return bool
     */
    public function hasErrors()
    {
        return (bool)count($this->_errors);
    }

    /**
     * Get array of errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Sets the target Size
     *
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size = 0)
    {
        $this->_size = $size;

        return $this;
    }

    /**
     * Get the size of the Target
     *
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Format size
     *
     * @param $bytes
     *
     * @return string
     */
    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    /**
     * Get Target Data As Array
     *
     * @param bool $json - Flag to determine if output should be JSON encoded
     *
     * @return array|string
     */
    public function getAsArray($json = false)
    {
        $output = array('url' => $this->getUrl(), 'size' => $this->formatSizeUnits($this->getSize()));

        return $json ? json_encode($output) : $output;
    }
}