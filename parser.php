<?php

/**
 * Class Parser - Handles retrieving target and parsing size
 */
class Parser
{
    /**
     * Target Object to parse
     *
     * @var Target
     */
    private $_target;

    /**
     * Assign Target.  If not Target object, but string, attempt to create Target Object
     *
     * @param bool $target
     */
    public function __construct($target = false)
    {
        $this->setTarget($target);
    }

    /**
     * Handle retrieving target details
     */
    public function parse()
    {
        // If Target is set, parse
        if ($target = $this->getTarget()) {
            // Perform cURL request and get Results
            $curlResponse = $this->curlRequest($target->getUrl());

            // Check response for errors, get size if good
            if ($curlResponse['error']) {
                $target->addError('cURL Request Failed: ' . $curlResponse['error']);
            } elseif (false === $curlResponse['result']) {
                $target->addError('cURL Request Failed: unknown');
            } else {
                $target->setSize($this->_getSize($curlResponse['result']));
            }
        } else {
            $target = new Target();
            $target->addError('No URL Specified');
        }

        return $target;
    }

    /**
     * Get the size of the file from the cURL results
     *
     * @param $curlResult
     *
     * @return int
     */
    private function _getSize($curlResult)
    {
        $size           = 0;
        $status         = 0;
        $content_length = 0;

        // Try to get the Status from the cURL results
        if (preg_match("/^HTTP\/1\.[01] (\d\d\d)/", $curlResult, $sMatches)) {
            $status = intval($sMatches[1]);
        }

        // Try to get the Content Length from the cURL results
        if (preg_match("/Content-Length: (\d+)/", $curlResult, $clMatches)) {
            $content_length = (int)$clMatches[1];
        }

        // If Status is good, set size to content length
        if (200 == $status || ($status > 300 && $status <= 308)) {
            $size = $content_length;
        }

        return $size;
    }

    /**
     * Perform cURL request and get response
     *
     * @param $targetUrl
     *
     * @return mixed
     */
    private function curlRequest($targetUrl)
    {
        $response = array('result' => false, 'error' => false);

        // Create cURL object
        $curl = curl_init($targetUrl);

        // Set cURL options to pull just header
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Set cURL option to allow following redirect to ensure reaching file
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $response['result'] = curl_exec($curl);

        if (false === $response) {
            $response['error'] = curl_error($curl);
        }

        // Close cURL object
        curl_close($curl);

        return $response;
    }

    /**
     * @param Target|string $target
     *
     * @return $this
     */
    public function setTarget($target)
    {
        if ($target instanceof Target) {
            $this->_target = $target;
        } elseif (is_string($target) && strlen($target)) {
            $this->_target = new Target($target);
        }

        return $this;
    }

    /**
     * @return Target
     */
    public function getTarget()
    {
        return $this->_target;
    }


}