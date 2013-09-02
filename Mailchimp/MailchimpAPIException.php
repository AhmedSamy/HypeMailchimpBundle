<?php

/**
 * Custom mailchimp exception class
 *
 * @license    http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link       https://github.com/AhmedSamy/hype-mailchimp-api-2.0
 * @since      version 1.0
 * @author Ahmed Samy <ahmed.samy.cs@gmail.com>
 */

namespace Hype\MailchimpBundle\Mailchimp;

class MailchimpAPIException extends \Exception {

    public function __construct($data) {
        parent::__construct(sprintf('Mailchimp API error : [ %s ] %s , code = %s', $data['name'], $data['error'], $data['code']));
    }

}