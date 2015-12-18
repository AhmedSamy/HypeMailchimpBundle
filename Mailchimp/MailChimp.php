<?php

namespace Hype\MailchimpBundle\Mailchimp;

use Hype\MailchimpBundle\Mailchimp\RestClient;

class MailChimp extends RestClient
{

    protected $dataCenter;
    protected $config;
    protected $listId;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->listId = $this->config['default_list'];
        $key = preg_split("/-/", $this->config['api_key']);

        if ($this->config['ssl']) {
            $this->dataCenter = 'https://' . $key[1] . '.api.mailchimp.com/';
        } else {
            $this->dataCenter = 'http://' . $key[1] . '.api.mailchimp.com/';
        }

        if (!function_exists('curl_init')) {
            throw new \Exception('This bundle needs the CURL PHP extension.');
        }

    }

    /**
     *
     * @param string $apiKey mailchimp API key
     */
    public function setApiKey($apiKey)
    {
        $this->config['api_key'] = $apiKey;
        $key = preg_split("/-/", $apiKey);

        if ($this->config['ssl']) {
            $this->dataCenter = 'https://' . $key[1] . '.api.mailchimp.com/';
        } else {
            $this->dataCenter = 'http://' . $key[1] . '.api.mailchimp.com/';
        }
    }

    /**
     * Verifies if given API key is equal to stored one.
     * 
     * @param string $apiKey mailchimp API key
     * 
     * @return bool Returns true if keys equal, false otherwise.
     */
    public function verifyApiKey($apiKey)
    {
        return $this->config['api_key'] == $apiKey;
    }

    /**
     * Set mailing list id
     *
     * @param string $listId mailing list id
     */
    public function setListID($listId)
    {
        $this->listId = $listId;
    }


    /**
     *
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCList
     */
    public function getList()
    {
        return new Methods\MCList($this->config, $this->listId, $this->dataCenter);
    }

    /**
     *
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCCampaign
     */
    public function getCampaign()
    {
        return new Methods\MCCampaign($this->config, $this->listId, $this->dataCenter);
    }

    /**
     *
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCExport
     */
    public function getExport()
    {
        return new Methods\MCExport($this->config, $this->listId, $this->dataCenter);
    }

    /**
     *
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCTemplate
     */
    public function getTemplate()
    {
        return new Methods\MCTemplate($this->config, $this->listId, $this->dataCenter);
    }

    /**
     *
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCHelper
     */
    public function getHelper()
    {
        return new Methods\MCHelper($this->config, $this->listId, $this->dataCenter);
    }

}
