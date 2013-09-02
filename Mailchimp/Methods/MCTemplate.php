<?php

/**
 * Manage mailchimp templates
 *
 * @license    http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link       https://github.com/AhmedSamy/hype-mailchimp-api-2.0
 * @since      version 1.0
 * @author Ahmed Samy <ahmed.samy.cs@gmail.com>
 */

namespace Hype\MailchimpBundle\Mailchimp\Methods;

use Hype\MailchimpBundle\Mailchimp\RestClient,
    Hype\MailchimpBundle\Mailchimp\MailchimpAPIException;

class MCTemplate extends RestClient {

    /**
     * Create a new user template, NOT campaign content. These templates can then be applied while creating campaigns.
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/templates/add.php
     * @param string $name
     * @param string $html
     * @param int $folderId
     * @return int template_id on success
     * @throws MailchimpAPIException
     */
    public function add($name, $html, $folderId = null) {
        $payload = array(
            'name' => $name,
            'html' => $html,
            'folder_id' => $folderId
        );
        $apiCall = 'templates/add';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data['template_id']) ? $data['template_id'] : false;
    }

    /**
     * Retrieve various templates available in the system, allowing some thing similar to our template gallery to be created.
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/templates/list.php
     * @param array $types optional
     * @param array $filters optional
     * @return array
     * @throws MailchimpAPIException
     */
    public function listAll($types = array(), $filters = array()) {
        $payload = array(
            'types' => $types,
            'filters' => $filters
        );
        $apiCall = 'templates/list';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return $data;
    }

    /**
     * Delete (deactivate) a user template
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/templates/del.php 
     * @param int $id template_id
     * @return boolean
     * @throws MailchimpAPIException
     */
    public function del($id) {
        $payload = array(
            'template_id' => $id
        );
        $apiCall = 'templates/del';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Pull details for a specific template to help support editing
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/templates/info.php
     * @param int $id template_id
     * @param string $type Optional
     * @return array
     * @throws MailchimpAPIException
     */
    public function info($id, $type = null) {
        $payload = array(
            'template_id' => $id,
            'type' => $type
        );
        $apiCall = 'templates/info';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return $data;
    }

    /**
     * Undelete (reactivate) a user template
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/templates/undel.php
     * @param int $id
     * @return boolean
     * @throws MailchimpAPIException
     */
    public function undel($id) {
        $payload = array(
            'template_id' => $id
        );
        $apiCall = 'templates/undel';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Replace the content of a user template, NOT campaign content.
     * 
     * @link http://apidocs.mailchimp.com/api/2.0/templates/update.php
     * @param type $id
     * @return boolean
     * @throws MailchimpAPIException
     */
    public function update($id, $options = array()) {
        $payload = array(
            'template_id' => $id,
            'values' => $options
        );
        $apiCall = 'templates/update';
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /*     * ********************************** Custom functions ***************************** */

    /**
     * Delete template by name
     * 
     * @param string $name
     * @return boolean
     * @throws MailchimpAPIException
     */
    public function delByName($name) {
        return $this->del($this->getIdByName($name));
    }

    /**
     * Get template id by Name
     * 
     * @param type $name name of the template
     * @return int|boolean $id template_id or false
     */
    public function getByName($name) {
        $templates = $this->listAll();
        if (empty($templates['user']))
            return false;
        foreach ($templates['user'] as $template) {
            if ($template['name'] === $name) {
                return $template['id'];
            }
        }
        return false;
    }

}
