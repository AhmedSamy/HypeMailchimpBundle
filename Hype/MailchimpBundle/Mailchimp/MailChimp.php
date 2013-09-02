<?php

namespace Hype\MailchimpBundle\Mailchimp;

use Hype\MailchimpBundle\Mailchimp\RestClient;

class MailChimp extends RestClient {

    protected $apiKey;
    protected $listId;
    protected $dataCenter;
    protected $container;

    public function __construct($container) {
        $this->container=$container;
        $this->apiKey = $this->container->getParameter('hype_mail_chimp.api_key');
        $this->listId = $this->container->getParameter('hype_mail_chimp.default_list');

        $key = preg_split("/-/", $this->apiKey);

        if ($this->container->getParameter('hype_mail_chimp.ssl')) {
            $this->dataCenter = 'https://' . $key[1] . '.api.mailchimp.com/';
        } else {
            $this->dataCenter = 'http://' . $key[1] . '.api.mailchimp.com/';
        }

        if (!function_exists('curl_init')) {
            throw new \Exception('This bundle needs the cURL PHP extension.');
        }

    }

    /**
     * Get Mailchimp api key
     *
     * @return string
     */
    public function getAPIkey() {
        return $this->apiKey;
    }

    /**
     * Set mailing list id
     *
     * @param string $listId mailing list id
     */
    public function setListID($listId) {
        $this->listId = $listId;
    }

    /**
     * get mailing list id
     *
     * @return string $listId
     */
    public function getListID() {
        return $this->listId;
    }

    /**
     * 
     * @return string
     */
    public function getDatacenter() {
        return $this->dataCenter;
    }

    /**
     * 
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCList
     */
    public function getList() {
        return new Methods\MCList($this->apiKey, $this->listId, $this->dataCenter);
    }

    /**
     * 
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCCampaign
     */
    public function getCampaign() {
        return new Methods\MCCampaign($this->apiKey, $this->listId, $this->dataCenter);
    }

    /**
     * 
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCExport
     */
    public function getExport() {
        return new Methods\MCExport($this->apiKey, $this->listId, $this->dataCenter);
    }

    /**
     * 
     * @return \Hype\MailchimpBundle\Mailchimp\MailChimpMethods\CustomMCTemplate
     */
    public function getTemplate() {
        return new Methods\MCTemplate($this->apiKey, $this->listId, $this->dataCenter);
    }

}
