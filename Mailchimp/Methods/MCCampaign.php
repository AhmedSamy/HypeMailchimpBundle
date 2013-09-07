<?php

/**
 * Campaign related functions
 *
 * @license    http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link       https://github.com/AhmedSamy/hype-mailchimp-api-2.0
 * @since      version 1.0
 * @author Ahmed Samy <ahmed.samy.cs@gmail.com>
 */

namespace Hype\MailchimpBundle\Mailchimp\Methods;

use Hype\MailchimpBundle\Mailchimp\RestClient,
    Hype\MailchimpBundle\Mailchimp\MailchimpAPIException;

class MCCampaign extends RestClient {

    protected $cid = null;

    /**
     * set list id
     * @param string $listId
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCList
     */
    public function setListId($listId) {
        $this->listId = $listId;
        return $this;
    }

    /**
     * Set Campgain id
     * @param int $cid
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCCampaign
     */
    public function setCi($cid) {
        $this->cid = $cid;
        return $this;
    }

    /**
     * Create a new draft campaign to send. You can not have more than 32,000 campaigns in your account.
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/create.php
     * @param string $type the Campaign Type has to be one of "regular", "plaintext", "absplit", "rss", "auto"
     * @param array $options
     * @param array $content
     * @param array $segment_opts
     * @param array $type_opts
     * @return array Campaign data
     * @throws \Exception
     * @throws MailchimpAPIException
     */
    public function create($type, $options = array(), $content = array(), $segment_opts = array(), $type_opts = array()) {
        if (!in_array($type, array("regular", "plaintext", "absplit", "rss", "auto"))) {
            throw new \Exception('the Campaign Type has to be one of "regular", "plaintext", "absplit", "rss", "auto" ');
        }
        $payload = array(
            'type' => $type,
            'options' => $options,
            'content' => $content,
            'segment_opts' => $segment_opts,
            'type_opts' => $type_opts
        );
        $apiCall = 'campaigns/create';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Get the content (both html and text) for a campaign either as it would appear in the campaign archive or as the raw, original content
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/content.php
     * @param array $options
     * @return array Campaign content
     * @throws MailchimpAPIException
     */
    public function content($options = array()) {
        $payload = array(
            'cid' => $this->cid,
            'options' => $options
        );
        $apiCall = 'campaigns/content';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Get the list of campaigns and their details matching the specified filters
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/list.php
     * @param array $filters
     * @param int $start start page
     * @param int $limit limit 
     * @param string $sort_field 
     * @param string $sort_dir
     * @return array
     * @throws \Exception
     * @throws MailchimpAPIException
     */
    public function get($filters = array(), $start = 0, $limit = 25, $sort_field = 'create_time', $sort_dir = "DESC") {
        if (!in_array(strtolower($sort_field), array("create_time", "send_time", "title", "subject")))
            throw new \Exception('sort_field  has to be one of "create_time", "send_time", "title", "subject" ');
        if (!in_array(strtoupper($sort_dir), array("ASC", "DESC")))
            throw new \Exception('sort_dir  has to be one of "ASC", "DESC" ');
        $payload = array(
            'filters' => $filters,
            'start' => $start,
            'limit' => $limit,
            'sort_field' => $sort_field,
            'sort_dir' => $sort_dir
        );
        $apiCall = 'campaigns/list';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Delete a campaign. Seriously, "poof, gone!" - be careful! Seriously, no one can undelete these.
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/delete.php
     * @return boolean
     * @throws MailchimpAPIException
     */
    public function del() {
        $payload = array(
            'cid' => $this->cid
        );
        $apiCall = 'campaigns/delete';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Pause an AutoResponder or RSS campaign from sending
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/pause.php
     * @return boolean
     * @throws MailchimpAPIException
     */
    public function pause() {
        $payload = array(
            'cid' => $this->cid
        );
        $apiCall = 'campaigns/pause';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Returns information on whether a campaign is ready to send and possible issues we may have detected with it - very similar to the confirmation step in the app.
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/ready.php
     * @return array
     * @throws MailchimpAPIException
     */
    public function ready() {
        $payload = array(
            'cid' => $this->cid
        );
        $apiCall = 'campaigns/ready';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Replicate a campaign.
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/replicate.php
     * @return boolean
     * @throws MailchimpAPIException
     */
    public function replicate() {
        $payload = array(
            'cid' => $this->cid
        );
        $apiCall = 'campaigns/replicate';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Resume sending an AutoResponder or RSS campaign
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/resume.php

     * @return boolean
     * @throws MailchimpAPIException
     */
    public function resume() {
        $payload = array(
            'cid' => $this->cid
        );
        $apiCall = 'campaigns/resume';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Send a given campaign immediately. For RSS campaigns, this will "start" them.
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/send.php
     * @return boolean
     * @throws MailchimpAPIException
     */
    public function send() {
        $payload = array(
            'cid' => $this->cid
        );
        $apiCall = 'campaigns/send';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Send a test of this campaign to the provided email addresses
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/send-test.php     
     * @param array $test_emails test email list
     * @param array $send_type "text" or "html"
     * @return boolean true on success
     * @throws \Exception
     * @throws MailchimpAPIException
     */
    public function sendTest($test_emails = array(), $send_type = 'html') {
        if (!in_array(strtoupper($sort_dir), array("html", "text")))
            throw new \Exception('send_type  has to be one of "html", "text" ');
        $payload = array(
            'cid' => $this->cid,
            'test_emails' => $test_emails,
            'send_type' => $send_type
        );
        $apiCall = 'campaigns/send-test';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Get the HTML template content sections for a campaign. Note that this will return very jagged, 
     * non-standard results based on the template a campaign is using. 
     * You only want to use this if you want to allow editing template sections in your application.
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/template-content.php 
     * @return boolean true on success
     * @throws MailchimpAPIException
     */
    public function templateContent() {
        $payload = array(
            'cid' => $this->cid
        );
        $apiCall = 'campaigns/template-content';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Allows one to test their segmentation rules before creating a campaign using them
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/segment-test.php
     * @param array $options optional
     * @return int The total number of subscribers matching your segmentation options 
     * @throws MailchimpAPIException
     */
    public function segmentTest($options = array()) {
        $payload = array(
            'list_id' => $this->listId,
            'options' => $options
        );
        $apiCall = 'campaigns/segment-test';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return $data['total'];
    }

    /**
     * Schedule a campaign to be sent in the future
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/schedule.php
     * @param string $schedule_time
     * @param string $schedule_time_b
     * @return boolean true on success
     * @throws MailchimpAPIException
     */
    public function schedule($schedule_time, $schedule_time_b = null) {
        $payload = array(
            'cid' => $this->cid,
            'schedule_time' => $schedule_time,
            'schedule_time_b' => $schedule_time_b
        );
        $apiCall = 'campaigns/schedule';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Schedule a campaign to be sent in batches sometime in the future. Only valid for "regular" campaigns
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/schedule-batch.php
     * @param string $schedule_time
     * @param int $num_batches
     * @param int $stagger_mins
     * @return boolean true on success
     * @throws MailchimpAPIException
     */
    public function scheduleBatch($schedule_time, $num_batches = 2, $stagger_mins = 5) {
        $payload = array(
            'cid' => $this->cid,
            'schedule_time' => $schedule_time,
            'num_batches' => $num_batches,
            'stagger_mins' => $stagger_mins
        );
        $apiCall = 'campaigns/schedule-batch';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Unschedule a campaign that is scheduled to be sent in the future
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/unschedule.php
     * @return boolean true on success
     * @throws MailchimpAPIException
     */
    public function unschedule() {
        $payload = array(
            'cid' => $this->cid
        );
        $apiCall = 'campaigns/unschedule';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Update just about any setting besides type for a campaign that has not been sent
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/campaigns/update.php
     * @param string $id the Campaign Id to update 
     * @param string $name parameter name
     * @param string $value parameter value
     * @return boolean true on success
     * @throws MailchimpAPIException
     */
    public function update($name, $value) {
        $payload = array(
            'cid' => $this->cid,
            'name' => $name,
            'value' => $value
        );
        $apiCall = 'campaigns/update';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

}
