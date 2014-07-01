<?php
require_once('target.php');
require_once('parser.php');


class UrlSize
{
    /**
     * @var array Arguments
     */
    protected $_args = array();

    public function __construct()
    {
        $this->_parseArgs();
    }

    public function run()
    {
        if (0 == count($this->getArgs()) || $this->getArg('h') || $this->getArg('help')) {
            die($this->_showHelp());
        } else {

            $parser  = new Parser();
            $results = array();

            if ($target = $this->_getTarget()) {
                $oTarget = new Target($target);
                $resultTarget = $parser->setTarget($oTarget)->parse();
                $results[] = $resultTarget->getAsArray();
            }
            if ($source = $this->_getSource()) {
                $targets = $this->_getSourceTargets();
                foreach ($targets as $target) {

                }
            }
        }
    }

    /**
     * Retrieve all provided arguments
     *
     * @return array
     */
    protected function getArgs()
    {
        return $this->_args;
    }

    /**
     * Retrieve Argument value by key
     *
     * @param $arg
     *
     * @return bool
     */
    protected function getArg($arg)
    {
        if (isset($this->_args[$arg])) {
            return $this->_args[$arg];
        }

        return false;
    }

    /**
     * Retrieve Target argument (-t or --target)
     *
     * @return bool
     */
    protected function _getTarget()
    {
        $target = false;
        if ($t = $this->getArg('t')) {
            $target = $t;
        } elseif ($t = $this->getArg('target')) {
            $target = $t;
        }

        return $target;
    }

    /**
     * Retrieve Source argument (-s or --source)
     *
     * @return bool
     */
    protected function _getSource()
    {
        $source = false;
        if ($s = $this->getArg('s')) {
            $source = $s;
        } elseif ($s = $this->getArg('source')) {
            $source = $s;
        }

        return $source;
    }

    protected function _getSourceTargets()
    {
        if (file_exists($this->_getSource())) {
            
        }
    }

    /**
     * Display help text
     *
     * @return string
     */
    protected function _showHelp()
    {
        return <<<USAGE
Usage:  php -f urlsize.php -- <params>

  -h --help                     Shows help

Params:

  -t --target <target url>       Will parse the url and get the size of the file
  -d --dest <file>               Will write results to specified file, will write to screen if not supplied
  -s --source <file>             Will read source file JSON array of targets


USAGE;
    }

    /**
     * Parse args
     *
     * @return $this
     */
    protected function _parseArgs()
    {
        $current = null;
        foreach ($_SERVER['argv'] as $arg) {
            $match = array();
            if (preg_match('#^--([\w\d_-]{1,})$#', $arg, $match) || preg_match('#^-([\w\d_]{1,})$#', $arg, $match)) {
                $current = $match[1];
                $this->_args[$current] = true;
            } else {
                if ($current) {
                    $this->_args[$current] = $arg;
                } else if (preg_match('#^([\w\d_]{1,})$#', $arg, $match)) {
                    $this->_args[$match[1]] = true;
                }
            }
        }
        return $this;
    }
}

$urlSize = new UrlSize();
$urlSize->run();