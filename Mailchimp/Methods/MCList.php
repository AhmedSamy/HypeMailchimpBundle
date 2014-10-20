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

class MCList extends RestClient
{

    protected $merge_vars = array();
    protected $grouping_id = NULL;
    protected $group_name = NULL;

    /**
     * set list id
     * @param string $listId
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCList
     */
    public function setListId($listId)
    {
        $this->listId = $listId;
        return $this;
    }

    /**
     * set grouping id
     * @param int $grouping_id grouping id
     * @return \Hype\MailchimpBundle\Mailchimp\Methods\MCList
     */
    public function setGrouping_id($grouping_id)
    {
        $this->grouping_id = $grouping_id;
        return $this;
    }

    /**
     * Add to merge vars array
     *
     * @param mix $merge_vars
     */
    public function addMerge_vars($merge_vars) {
        $this->merge_vars=array_merge($this->merge_vars,$merge_vars);
        return $this;
    }

    /**
     * set to merge vars
     * @param mix $merge_vars
     */
    public function setMerge_vars($merge_vars)
    {
        $this->merge_vars = $merge_vars;
        return $this;
    }

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
    public function abuseReport($start = 0, $limit = 2000, $string = null)
    {
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
    public function getActivity()
    {
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

    /**
     * Subscribe a batch of email addresses to a list at once,
     * These calls are also long, so be sure you increase your timeout values
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/batch-subscribe.php
     * @param string $batch - array of arrays with ['email','email_type','merge_vars']
     * @param boolean $double_optin optional
     * @param boolean $update_existing optional
     * @param boolean $replace_interests optional
     * @return array
     * @throws MailchimpAPIException
     **/
    public function batchSubscribe($batch, $double_optin = true, $update_existing = true, $replace_interests = true) {
        $payload = array(
            'id' => $this->listId,
            'batch' => $batch,
            'double_optin' => $double_optin,
            'update_existing' => $update_existing,
            'replace_interests' => $replace_interests,
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
     * Unsubscribe a batch of email addresses to a list at once,
     * These calls are also long, so be sure you increase your timeout values
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/batch-unsubscribe.php
     * @param string $batch - array of arrays with ['email','email_type','merge_vars']
     * @param boolean $delete_member optionnal
     * @param boolean $send_goodbye optionnal
     * @param boolean $send_notify optionnal
     * @return array
     * @throws MailchimpAPIException
     **/
    public function batchUnsubscribe($batch, $delete_member = false, $send_goodbye = false, $send_notify = false) {
        $payload = array(
            'id' => $this->listId,
            'batch' => $batch,
			'delete_member' => $delete_member,
			'send_goodbye' => $send_goodbye,
			'send_notify' => $send_notify
        );
        $apiCall = 'lists/batch-unsubscribe';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Subscribe an email addresses to a list,
     * These calls are also long, so be sure you increase your timeout values
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/subscribe.php
     * @param string $email
     * @param string $email_type
     * @param boolean $double_optin optional
     * @param boolean $update_existing optional
     * @param boolean $replace_interests optional
     * @param boolean $send_welcome optional
     * @param string $email_identifier optional can be (email,euid, leid)
     * @return array
     * @throws MailchimpAPIException
     **/
    public function subscribe($email_id, $email_type = 'html', $double_optin = true, $update_existing = true, $replace_interests = true, $send_welcome = false, $email_identifier = 'email') {
        if (!in_array($email_identifier, array("email", "euid", "leid")))
            throw new InvalidArgumentException('email identifier should be one of ("email","euid","leid")');

        $payload = array(
            'id' => $this->listId,
            'email' => array(
                $email_identifier => $email_id
            ),
            'merge_vars' => $this->merge_vars,
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
    public function unsubscribe($email_id, $delete_member = false, $send_goodbye = true, $send_notify = true, $email_identifier = 'email')
    {

        if (!in_array($email_identifier, array("email", "euid", "leid")))
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
     * @param mix $email_id email id or ids array of emails or string
     * @param string $email_identifier optional can be (email,euid, leid)
     * @return array
     * @throws InvalidArgumentException
     */
    public function memberInfo($email_id, $email_identifier = 'email')
    {
        if (!in_array($email_identifier, array("email", "euid", "leid")))
            throw new InvalidArgumentException('email identifier should be one of ("email","euid","leid")');
        $email_ids = array();
        if (is_array($email_id))
        {
            foreach ($email_id as $email) {
                $email_ids[] = array($email_identifier => $email);
            }
        } else {
            $email_ids[] = array($email_identifier => $email_id);
        }
        $payload = array(
            'id' => $this->listId,
            'emails' => $email_ids
        );
        $apiCall = 'lists/member-info';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Get a list of members for a list
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/members.php
     * @param string $status optional 'subscribed', 'unsubscribed', 'cleaned'
     * @param array $opts optional
     * @return array
     * @throws InvalidArgumentException
     */
    public function members($status = 'subscribed', $opts = null) {

        $payload = array(
            'id' => $this->listId,
            'status' => $status
        );

        if (!is_null($opts)) {
            $payload['opts'] = $opts;
        }

        $apiCall = 'lists/members';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;

    }
    
    /**
     * Retrieve all of the lists defined for your user account
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/lists/list.php
     * @param array $filters optional - filters to apply to this query 
     * @param integer $start optional - optional - control paging of lists, start results at this list #, defaults to 1st page of data (page 0) 
     * @param integer $end optional - optional - control paging of lists, number of lists to return with each call, defaults to 25 (max=100) 
     * @param string $sort_field optional - optional - "created" (the created date, default) or "web" (the display order in the web app). Invalid values will fall back on "created" - case insensitive. 
     * @param string $sort_dir optional - optional - "DESC" for descending (default), "ASC" for Ascending. Invalid values will fall back on "created" - case insensitive. Note: to get the exact display order as the web app you'd use "web" and "ASC" 
     * @return array lists
     * @throws MailchimpAPIException
     */
    public function lists($filters = array(), $start=0, $end=100, $sort_field="created", $sort_dir="DESC")
    {

        $payload = array(
            'id' => $this->listId,
            'filters' => $filters,
            'start' => $start,
            'end' => $end,
            'sort_field' => $sort_field,
            'sort_dir' => $sort_dir
        );
        $apiCall = 'lists/list';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Edit the email address, merge fields, and interest groups for a list member. If you are doing a batch update on lots of users, consider using listBatchSubscribe() with the update_existing and possible replace_interests parameter.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/update-member.php
     * @param string $email_id optinal
     * @param string $email_type optional can be "html" or "text", defaults "html"
     * @param boolean $replace_interests optional
     * @param string $email_identifier optional can be (email,euid, leid)
     * @return array email information (email,euid, leid)
     * @throws InvalidArgumentException
     * @throws MailchimpAPIException
     */
    public function updateMember($email_id, $email_type = 'html', $replace_interests = true, $email_identifier = 'email')
    {
        if (!in_array($email_identifier, array("email", "euid", "leid")))
            throw new InvalidArgumentException('email identifier should be one of ("email","euid","leid")');

        $payload = array(
            'id' => $this->listId,
            'email' => array(
                $email_identifier => $email_id
            ),
            'merge_vars' => $this->merge_vars,
            'email_type' => $email_type,
            'replace_interests' => $replace_interests
        );

        $apiCall = 'lists/update-member';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Get the list of interest groupings for a given list, including the label, form information, and included groups for each
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-groupings.php
     * @param bool $count optional wether to get subscriber count or not
     * @return array all groups information for specific list
     * @throws MailchimpAPIException
     */
    public function interestGroupings($count = null)
    {

        $payload = array(
            'id' => $this->listId,
            'count' => $count
        );

        $apiCall = 'lists/interest-groupings';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Add a new Interest Grouping - if interest groups for the List are not yet enabled, adding the first grouping will automatically turn them on.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-grouping-add.php
     * @param string $name the interest grouping to add - grouping names must be unique
     * @param string $type The type of the grouping to add - one of "checkboxes", "hidden", "dropdown", "radio"
     * @param array $groups The lists of initial group names to be added - at least 1 is required and the names must be unique within a grouping. If the number takes you over the 60 group limit
     * @return array contains id of the new group
     * @throws MailchimpAPIException
     */
    public function addInterestGroupings($name, $type, array $groups)
    {

        $payload = array(
            'id' => $this->listId,
            'name' => $name,
            'type' => $type,
            'groups' => $groups
        );

        $apiCall = 'lists/interest-grouping-add';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Delete an existing Interest Grouping - this will permanently delete all contained interest groups and will remove those selections from all list members
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-grouping-del.php
     * @param int $group_id optional the interest grouping id
     * @return boolean true on success
     * @throws MailchimpAPIException
     */
    public function delInterestGrouping($group_id = false)
    {

        $payload = array(
            'grouping_id' => (FALSE === $group_id) ? $this->grouping_id : $group_id
        );

        $apiCall = 'lists/interest-grouping-del';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Update an existing Interest Grouping
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-grouping-update.php
     * @param string $name The name of the field to update - either "name" or "type". Groups within the grouping should be manipulated using the standard listInterestGroup* methods
     * @param string $value The new value of the field. Grouping names must be unique - only "hidden" and "checkboxes" grouping types can be converted between each other.
     * @param int $group_id optional unless not has been set before
     * @return boolean true on success
     * @throws MailchimpAPIException
     */
    public function updateInterestGrouping($name, $value, $group_id = false)
    {

        $payload = array(
            'grouping_id' => (FALSE === $group_id) ? $this->grouping_id : $group_id,
            'name' => $name,
            'value' => $value
        );

        $apiCall = 'lists/interest-grouping-update';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Add a single Interest Group - if interest groups for the List are not yet enabled, adding the first group will automatically turn them on.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-group-add.php
     * @param string $name the interest group to add - group names must be unique within a grouping
     * @param int $group_id optional The grouping to add the new group to - get using listInterestGrouping() . If not supplied, the first grouping on the list is used.
     * @return boolean true on success
     * @throws MailchimpAPIException
     */
    public function addInterestGroup($name, $group_id = NULL)
    {

        $payload = array(
            'id' => $this->listId,
            'group_name' => $name,
            'grouping_id' => (NULL === $group_id) ? $this->grouping_id : $group_id,
        );

        $apiCall = 'lists/interest-group-add';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Change the name of an Interest Group
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-group-update.php
     * @param string $old_name the interest group name to be changed
     * @param string $new_name the new interest group name to be set
     * @param int $grouping_id optional  The grouping to delete the group from  If not supplied, the first grouping on the list is used.
     * @return boolean true on success
     * @throws MailchimpAPIException
     */
    public function updateInterestGroup($old_name, $new_name, $grouping_id = NULL)
    {

        $payload = array(
            'id' => $this->listId,
            'old_name' => $old_name,
            'new_name' => $new_name,
            'grouping_id' => (NULL === $grouping_id) ? $this->grouping_id : $grouping_id
        );

        $apiCall = 'lists/interest-group-update';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Delete a single Interest Group - if the last group for a list is deleted, this will also turn groups for the list off.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-group-del.php
     * @param string $name the name of interest group to delete
     * @param int $grouping_id optional The grouping to delete the group from. If not supplied, the first grouping on the list is used
     * @return boolean true on success
     * @throws MailchimpAPIException
     */
    public function delInterestGroup($name, $grouping_id = NULL)
    {

        $payload = array(
            'id' => $this->listId,
            'group_name' => $name,
            'grouping_id' => (NULL === $grouping_id) ? $this->grouping_id : $grouping_id,
        );

        $apiCall = 'lists/interest-group-del';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }
    
    /**
     * Save a segment against a list for later use. - no limit
     * After creating the segment, add members with addMembersStaticSegment
     * @link http://apidocs.mailchimp.com/api/2.0/lists/static-segment-members-add.php
     *
     * @param $name - Name of segment
     * @return bool/int - ID of new segment
     * @throws MailchimpAPIException
     */
    public function addStaticSegment($name) {
        $payload = array(
            'id' => $this->listId,
            'name' => $name,
        );

        $apiCall = 'lists/static-segment-add';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);

        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data['id']) ? $data['id'] : false;
    }

    /**
     * Save members to a static segment
     * @link http://apidocs.mailchimp.com/api/2.0/lists/static-segment-members-add.php
     *
     * @param $seg_id
     * @param $batch - array of emails and uuid
     * @return bool
     * @throws MailchimpAPIException
     */
    public function addMembersStaticSegment($seg_id, $batch) {
        $payload = array(
            'id' => $this->listId,
            'seg_id' => $seg_id,
            'batch' => $batch
        );

        $apiCall = 'lists/static-segment-members-add';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);

        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Retrieve all of the Static Segments for a list.
     * @link http://apidocs.mailchimp.com/api/2.0/lists/static-segments.php
     *
     * @return bool|mixed
     * @throws \Hype\MailchimpBundle\Mailchimp\MailchimpAPIException
     */
    public function listStaticSegments() {
        $payload = array(
            'id' => $this->listId,
        );

        $apiCall = 'lists/static-segments';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);

        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

}
