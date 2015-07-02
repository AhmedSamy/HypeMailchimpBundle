<?php

/**
 * Helper related functions
 *
 * @license    http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link       https://github.com/AhmedSamy/hype-mailchimp-api-2.0
 * @since      version 1.0
 * @author     Gabriele Perego <gabriele.perego@me.com>
 */

namespace Hype\MailchimpBundle\Mailchimp\Methods;

use Hype\MailchimpBundle\Mailchimp\RestClient,
    Hype\MailchimpBundle\Mailchimp\MailchimpAPIException;

class MCHelper extends RestClient
{

    /**
     * Ping the MailChimp API
     *
     * @return string
     * @throws MailchimpAPIException
     */
    public function ping()
    {
        $apiCall = '/helper/ping';
        $payload = "";
        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return $data['msg'];
    }

    /**
     * Have HTML content auto-converted to a text-only format. You can send: plain HTML, an existing Campaign Id, or an existing Template Id
     *
     * @param string $type
     * @param array $content
     * @return array
     * @throws MailchimpAPIException
     */
    public function generateText($type = 'html', $content = array())
    {
        $apiCall = '/helper/generate-text';
        $payload = array(
            'type'      => $type,
            'content'   => $content
        );

        $data = $this->requestMonkey($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return $data;
    }

}
