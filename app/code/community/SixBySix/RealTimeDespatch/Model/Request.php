<?php

/**
 * Request Model.
 */
class SixBySix_RealTimeDespatch_Model_Request extends Mage_Core_Model_Abstract
{
    /**
     * Model Event Prefix.
     *
     * @var string
     */
    protected $_eventPrefix = 'realtimedespatch_request';

    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/request');
    }

    /**
     * Sets the request body.
     *
     * @param string $requestBody
     *
     * @return SixBySix_RealTimeDespatch_Model_Request
     */
    public function setRequestBody($requestBody)
    {
        return parent::setRequestBody(gzdeflate($requestBody, 9));
    }

    /**
     * Returns the request body.
     *
     * @return string
     */
    public function getRequestBody()
    {
        if ( ! parent::getRequestBody()) {
            return 'Logs Cleaned';
        }

        $xml = '';

        try
        {
            $dom = new \DOMDocument;
            $dom->preserveWhiteSpace = false;
            $dom->loadXML(gzinflate(parent::getRequestBody()));
            $dom->formatOutput = true;
            $xml = $dom->saveXml();
        }
        catch (\Exception $ex)
        {
            $xml = 'Request Unavailable';
        }

        return $xml;
    }

    /**
     * Sets the response body.
     *
     * @param string $responseBody
     *
     * @return SixBySix_RealTimeDespatch_Model_Request
     */
    public function setResponseBody($responseBody)
    {
        return parent::setResponseBody(gzdeflate($responseBody, 9));
    }

    /**
     * Returns the response body.
     *
     * @return string
     */
    public function getResponseBody()
    {
        if ( ! parent::getResponseBody()) {
            return 'Logs Cleaned';
        }

        return gzinflate(parent::getResponseBody());
    }

    /**
     * Returns the line attached to the requests.
     *
     * @return array
     */
    public function getLines()
    {
        return Mage::getResourceModel('realtimedespatch/request_line_collection')
                    ->addFieldToFilter('request_id', array('eq' => $this->getId()))
                    ->setOrder('sequence_id', 'ASC');
    }

    /**
     * Checks whether the request can be processed.
     *
     * @return mixed
     */
    public function canProcess()
    {
        return ! $this->getProcessed();
    }

    /**
     * Returns the processed timestamp.
     *
     * @return mixed
     */
    public function getProcessed()
    {
        return Mage::getResourceModel('realtimedespatch/request_line_collection')
                    ->addFieldToFilter('request_id', array('eq' => $this->getId()))
                    ->setOrder('processed', 'ASC')
                    ->setPageSize(1)
                    ->setCurPage(1)
                    ->getFirstItem()
                    ->getProcessed();
    }

    /**
     * Returns the related import.
     *
     * @return mixed
     */
    public function getImport()
    {
        return Mage::getResourceModel('realtimedespatch/import_collection')
                    ->addFieldToFilter('message_id', array('eq' => $this->getMessageId()))
                    ->addFieldToFilter('entity', array('eq' => $this->getType()))
                    ->setPageSize(1)
                    ->setCurPage(1)
                    ->getFirstItem();
    }
}