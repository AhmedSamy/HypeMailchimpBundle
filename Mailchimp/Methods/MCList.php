<?php

/**
 * List and Groups related functions
 *
 * @license    http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link       https://github.com/AhmedSamy/hype-mailchimp-api-2.0
 * @since      version 1.0
 * @author Ahmed Samy <ahmed.samy.cs@gmail.com>
 */

namespace Hype\MailchimpBundle\Mailchimp\Methods;

use Hype\MailchimpBundle\Mailchimp\RestClient,
    Hype\MailchimpBundle\Mailchimp\MailchimpAPIException,
    Buzz\Exception\InvalidArgumentException as InvalidArgumentException;

class MCList extends RestClient {

    /**
     * Get all email addresses that complained about a campaign sent to a list
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/lists/abuse-reports.php
     * @param int $start
     * @param int $limit
     * @param string $string
     * @return array
     * @throws MailchimpAPIException
     */
    public function abuseReport($start = 0, $limit = 2000, $string = null) {
        $payload = array(
            'id' => $this->listId,
            'start' => $start,
            'limit' => $limit,
            'string' => $string
        );
        $apiCall = 'lists/abuse-reports';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Access up to the previous 180 days of daily detailed aggregated activity stats for a given list. Does not include AutoResponder activity.
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/lists/activity.php
     * @return array
     * @throws MailchimpAPIException
     */
    public function activity() {
        $payload = array(
            'id' => $this->listId
        );
        $apiCall = 'lists/activity';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    public function batchSubscribe() {
        $payload = array(
            'id' => $this->listId
        );
        $apiCall = 'lists/batch-subscribe';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Subscribe a batch of email addresses to a list at once,
     * These calls are also long, so be sure you increase your timeout values
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/lists/batch-subscribe.php
     * @param string $email
     * @param array $merge_vars
     * @param string $email_type
     * @param boolean $double_optin optional
     * @param boolean $update_existing optional
     * @param boolean $replace_interests optional
     * @param boolean $send_welcome optional
     * @param string $email_identifier optional can be (email,euid, leid)
     * @return array
     * @throws MailchimpAPIException
     */
    public function Subscribe($email_id, $merge_vars = array(), $email_type = 'html', $double_optin = true, $update_existing = true, $replace_interests = true, $send_welcome = false, $email_identifier = 'email') {
        if (!in_array($email_id, array("email", "euid", "leid")))
            throw new InvalidArgumentException('email identifier should be one of ("email","euid","leid")');

        $payload = array(
            'id' => $this->listId,
            'email' => array(
                $email_identifier => $email_id
            ),
            'merge_vars' => array($merge_vars),
            'email_type' => $email_type,
            'double_optin' => $double_optin,
            'update_existing' => $update_existing,
            'replace_interests' => $replace_interests,
            'send_welcome' => $send_welcome
        );

        $apiCall = 'lists/subscribe';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Unsubscribe the given email address from the list
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/lists/unsubscribe.php
     * @param string $email_id
     * @param boolean $delete_member
     * @param boolean $send_goodbye
     * @param boolean $send_notify
     * @param string $email_identifier optional can be (email,euid, leid)
     * @return boolean true on success
     * @throws InvalidArgumentException
     * @throws MailchimpAPIException
     */
    public function unsubscribe($email_id, $delete_member = false, $send_goodbye = true, $send_notify = true, $email_identifier = 'email') {

        if (!in_array($email_id, array("email", "euid", "leid")))
            throw new InvalidArgumentException('email identifier should be one of ("email","euid","leid")');

        $payload = array(
            'id' => $this->listId,
            'email' => array(
                $email_identifier => $email_id
            ),
            'delete_member' => $delete_member,
            'send_goodbye' => $send_goodbye,
            'send_notify' => $send_notify
        );

        $apiCall = 'lists/unsubscribe';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Get all the information for particular members of a list
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/lists/member-info.php
     * @param string $email_id
     * @param string $email_identifier optional can be (email,euid, leid)
     * @return array
     * @throws InvalidArgumentException
     */
    public function memberInfo($email_id, $email_identifier = 'email') {
        if (!in_array($email_id, array("email", "euid", "leid")))
            throw new InvalidArgumentException('email identifier should be one of ("email","euid","leid")');
        $payload = array(
            'id' => $this->listId,
            'email' => array(
                $email_identifier => $email_id
        ));
        $apiCall = 'lists/member-info';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /*************************************/
    public function UpdateMember(array $vars = array()) {
        $payload = array('email_address' => $this->email,
            'merge_vars' => $vars + $this->mergeVars,
            'email_type' => $this->emailType,);

        $apiCall = 'listUpdateMember';
        $data = $this->requestMonkey($apiCall, $payload);
        return json_decode($data);
    }

    public function listInterestGroupingAdd($name, $type, $groups = array()) {
        $payload = array(
            'name' => $name,
            'type' => $type,
            'groups' => $groups,
        );
        $apiCall = 'listInterestGroupingAdd';
        $data = $this->requestMonkey($apiCall, $payload);
        return json_decode($data);
    }

    public function getInterestGroupings() {
        $payload = array();
        $apiCall = 'listInterestGroupings';
        $data = $this->requestMonkey($apiCall, $payload);

        return json_decode($data);
    }

    public function listInterestGroupUpdate($oldName, $newName) {
        $payload = array(
            'old_name' => $oldName,
            'new_name' => $newName,
            'grouping_id' => $this->groupingId,
        );
        $apiCall = 'listInterestGroupUpdate';
        $data = $this->requestMonkey($apiCall, $payload);
        return json_decode($data);
    }

    public function listInterestGroupAdd($name) {
        $payload = array(
            'group_name' => $name,
            'grouping_id' => $this->groupingId,
        );
        $apiCall = 'listInterestGroupAdd';
        $data = $this->requestMonkey($apiCall, $payload);
        return json_decode($data);
    }

    public function listInterestGroupDel($name) {
        $payload = array(
            'group_name' => $name,
            'grouping_id' => $this->groupingId,
        );
        $apiCall = 'listInterestGroupDel';
        $data = $this->requestMonkey($apiCall, $payload);
        return json_decode($data);
    }

}
