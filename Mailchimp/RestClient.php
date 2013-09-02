<?php

namespace Hype\MailchimpBundle\Mailchimp;

use Buzz\Browser,
    Buzz\Client\Curl;

class RestClient {

    protected $dataCenter;
    protected $apiKey;
    protected $listId;

    /**
     * 
     * @param type $apiKey
     * @param type $listId
     * @param type $dataCenter
     */
    public function __construct($apiKey, $listId, $dataCenter) {
        $this->apiKey = $apiKey;
        $this->listId = $listId;
        $this->dataCenter = $dataCenter;
    }

    /**
     * Prepare the curl request 
     * 
     * @param string $apiCall the API call function
     * @param array $payload Parameters
     * @param boolean $export indicate wether API used is Export API or not
     * @return array
     */
    protected function requestMonkey($apiCall, $payload, $export = false) {
        $payload['apikey'] = $this->apiKey;

        if ($export) {
            $url = $this->dataCenter . $apiCall;
        } else {
            $url = $this->dataCenter . '2.0/' . $apiCall;
        }
        $curl = new Curl();
        $curl->setOption(CURLOPT_USERAGENT, 'HypeMailchimp');
        $payload = json_encode($payload);

        //to avoid ssl certificate error 
        $curl->setVerifyPeer(false);
        $browser = new Browser($curl);

        $headers = array(
            "Accept" => "application/json",
            "Content-type" => "application/json"
        );
        $response = $browser->post($url, $headers, ($payload));

        return $response->getContent();
    }

}
