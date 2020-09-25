<?php

namespace CapsuleB\AmazonMWS;

use CapsuleB\AmazonMWS\Resources\Products;
use Exception;
use SimpleXMLElement;

/**
 * Class Client
 * @package CapsuleB\AmazonMWS
 *
 * @property Products $products
 */
class Client {

  /**
   * @see https://docs.developer.amazonservices.com/en_US/dev_guide/DG_Endpoints.html
   */
  const ENDPOINTS = [
    // North America region
    [
      'marketplace'     => 'Brazil',
      'country_code'    => 'BR',
      'endpoint'        => 'mws.amazonservices.com',
      'marketplace_id'  => 'A2Q3Y263D00KWC'
    ],
    [
      'marketplace'     => 'Canada',
      'country_code'    => 'CA',
      'endpoint'        => 'mws.amazonservices.ca',
      'marketplace_id'  => 'A2EUQ1WTGCTBG2'
    ],
    [
      'marketplace'     => 'Mexico',
      'country_code'    => 'MX',
      'endpoint'        => 'mws.amazonservices.com.mx',
      'marketplace_id'  => 'A1AM78C64UM0Y8'
    ],
    [
      'marketplace'     => 'US',
      'country_code'    => 'US',
      'endpoint'        => 'mws.amazonservices.com',
      'marketplace_id'  => 'ATVPDKIKX0DER'
    ],

    // Europe region
    [
      'marketplace'     => 'United Arab Emirates (U.A.E.)',
      'country_code'    => 'AE',
      'endpoint'        => 'mws.amazonservices.ae',
      'marketplace_id'  => 'A2VIGQ35RCS4UG'
    ],
    [
      'marketplace'     => 'Germany',
      'country_code'    => 'DE',
      'endpoint'        => 'mws-eu.amazonservices.com',
      'marketplace_id'  => 'A1PA6795UKMFR9'
    ],
    [
      'marketplace'     => 'Egypt',
      'country_code'    => 'EG',
      'endpoint'        => 'mws-eu.amazonservices.com',
      'marketplace_id'  => 'ARBP9OOSHTCHU'
    ],
    [
      'marketplace'     => 'Spain',
      'country_code'    => 'ES',
      'endpoint'        => 'mws-eu.amazonservices.com',
      'marketplace_id'  => 'A1RKKUPIHCS9HS'
    ],
    [
      'marketplace'     => 'France',
      'country_code'    => 'FR',
      'endpoint'        => 'mws-eu.amazonservices.com',
      'marketplace_id'  => 'A13V1IB3VIYZZH'
    ],
    [
      'marketplace'     => 'UK',
      'country_code'    => 'GB',
      'endpoint'        => 'mws-eu.amazonservices.com',
      'marketplace_id'  => 'A1F83G8C2ARO7P'
    ],
    [
      'marketplace'     => 'India',
      'country_code'    => 'IN',
      'endpoint'        => 'mws.amazonservices.in',
      'marketplace_id'  => 'A21TJRUUN4KGV'
    ],
    [
      'marketplace'     => 'Italy',
      'country_code'    => 'IT',
      'endpoint'        => 'mws-eu.amazonservices.com',
      'marketplace_id'  => 'APJ6JRA9NG5V4'
    ],
    [
      'marketplace'     => 'Netherlands',
      'country_code'    => 'NL',
      'endpoint'        => 'mws-eu.amazonservices.com',
      'marketplace_id'  => 'A1805IZSGTT6HS'
    ],
    [
      'marketplace'     => 'Saudi Arabia',
      'country_code'    => 'SA',
      'endpoint'        => 'mws-eu.amazonservices.com',
      'marketplace_id'  => 'A17E79C6D8DWNP'
    ],
    [
      'marketplace'     => 'Turkey',
      'country_code'    => 'TR',
      'endpoint'        => 'mws-eu.amazonservices.com',
      'marketplace_id'  => 'A33AVAJ2PDY3EV'
    ],

    // Far East region
    [
      'marketplace'     => 'Singapore',
      'country_code'    => 'SG',
      'endpoint'        => 'mws-fe.amazonservices.com',
      'marketplace_id'  => 'A19VAU5U5O7RUS'
    ],
    [
      'marketplace'     => 'Australia',
      'country_code'    => 'AU',
      'endpoint'        => 'mws.amazonservices.com.au',
      'marketplace_id'  => 'A39IBJ37TRP1C6'
    ],
    [
      'marketplace'     => 'Japan',
      'country_code'    => 'JP',
      'endpoint'        => 'mws.amazonservices.jp',
      'marketplace_id'  => 'A1VC38T7YXB528'
    ],
  ];

  /**
   * @var string $baseUrl
   */
  private $baseUrl;

  /**
   * @var $requestHeader
   */
  private $requestHeader;

  /**
   * @var $curlClient
   */
  private $curlClient;

  /**
   * @var array $marketplace
   */
  private $marketplace;

  /**
   * @var string $sellerID
   */
  private $sellerID;

  /**
   * @var string $accessKey
   */
  private $accessKey;

  /**
   * @var string $secretAccessKey
   */
  private $secretAccessKey;

  /**
   * @var string $authToken
   */
  private $authToken;

  /**
   * Client constructor.
   * @param $marketplace
   * @param $sellerID
   * @param $accessKey
   * @param $secretAccessKey
   * @param null $authToken
   */
  public function __construct($marketplace, $sellerID, $accessKey, $secretAccessKey, $authToken = null) {
    // Init the Curl Client
    $this->curlClient = curl_init();

    // Init the infos
    $this->sellerID         = $sellerID;
    $this->accessKey        = $accessKey;
    $this->secretAccessKey  = $secretAccessKey;
    $this->authToken        = $authToken;

    // Set the marketplace
    foreach (self::ENDPOINTS as $endpoint) {
      if (in_array($marketplace, array_values($endpoint))) {
        $this->marketplace = $endpoint;
      }
    }

    // Set the base URL (based on selected marketplace)
    $this->baseUrl = 'https://' . $this->marketplace['endpoint'];

    // Init the header and query
    $this->initHeader();

    // Init the resources
    $this->products = new Products($this);
  }

  /**
   * Init the request header
   */
  private function initHeader() {
    $this->requestHeader = [
      'Content-Type: application/xml',
      'User-Agent: ' .  'capsule-b\/amazon-mws-php-lib/v1.x (Language=PHP/' . phpversion() . ')',
    ];
  }

  /**
   * @param string $method
   * @param array $base
   * @param string $action
   * @param array $query
   * @param array $params
   * @return array|mixed|object
   * @throws Exception
   */
  protected function request(string $method, array $base, string $action, $query = [], $params = []) {
    // Init the Curl Client
    $this->curlClient = curl_init();

    // Generate missing information for the query
    $query = array_merge($query, [
      'AWSAccessKeyId'    => $this->accessKey,
      'Action'            => $action,
      'SignatureVersion'  => 2,
      'SignatureMethod'   => 'HmacSHA256',
      'Timestamp'         => gmdate("Y-m-d\TH:i:s", time()),
      'Version'           => $base['version'],
      'SellerId'          => $this->sellerID,
      'MarketplaceId'     => $this->marketplace['marketplace_id'],
    ]);

    // Append the signature
    $query = array_merge($query, [
      'Signature' => $this->signature($method, $query, $base['path']),
    ]);

    // Add query params
    $path = $base['path'];
    if (!empty(array_filter($query))) {
      $path .= '?' . http_build_query(array_merge($query));
    }

    // Set the request params
    curl_setopt($this->curlClient, CURLOPT_URL, $this->baseUrl . $path);
    curl_setopt($this->curlClient, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->curlClient, CURLOPT_HEADER, false);
    curl_setopt($this->curlClient, CURLOPT_NOBODY, false);
    curl_setopt($this->curlClient, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($this->curlClient, CURLOPT_HTTPHEADER, $this->requestHeader);

    // Add params if any
    if (!empty($params)) {
      curl_setopt($this->curlClient, CURLOPT_POST, true);
      curl_setopt($this->curlClient, CURLOPT_POSTFIELDS, json_encode($params));
    }

    // Return headers seperatly from the Response Body
    $response   = curl_exec($this->curlClient);
    $headerSize = curl_getinfo($this->curlClient, CURLINFO_HEADER_SIZE);
    $httpCode   = curl_getinfo($this->curlClient, CURLINFO_HTTP_CODE);
    $headers    = $this->formatHeader(substr($response, 0, $headerSize));

    // Close the connection
    curl_close($this->curlClient);

    // Convert and return the response
    return $this->formatResponse($action, $response);
  }

  /**
   * GET Method
   *
   * @param array $base
   * @param string $action
   * @param array $query
   * @param array $params
   * @return mixed
   * @throws Exception
   */
  public function get(array $base, string $action, array $query = [], array $params = []) {
    return $this->request('GET', $base, $action, $this->wrap($query), $this->wrap($params));
  }

  /**
   * POST Method
   *
   * @param array $base
   * @param string $action
   * @param array $query
   * @param array $params
   * @return mixed
   * @throws Exception
   */
  public function post(array $base, string $action, array $query = [], array $params = []) {
    return $this->request('POST', $base, $action, $this->wrap($query), $this->wrap($params));
  }

  /**
   * PUT Method
   *
   * @param array $base
   * @param string $action
   * @param array $query
   * @param array $params
   * @return mixed
   * @throws Exception
   */
  public function put(array $base, string $action, array $query = [], array $params = []) {
    return $this->request('PUT', $base, $action, $this->wrap($query), $this->wrap($params));
  }

  /**
   * DELETE Method
   *
   * @param array $base
   * @param string $action
   * @param array $query
   * @param array $params
   * @return mixed
   * @throws Exception
   */
  public function delete(array $base, string $action, array $query = [], array $params = []) {
    return $this->request('DELETE', $base, $action, $this->wrap($query), $this->wrap($params));
  }

  /**
   * @param string $method
   * @param array $query
   * @param string $path
   * @return string
   */
  private function signature(string $method, array $query, string $path) {
    ksort($query);
    return base64_encode(
      hash_hmac(
        'sha256',
        $method
        . "\n"
        . $this->marketplace['endpoint']
        . "\n"
        . $path
        . "\n"
        . http_build_query($query, null, '&', PHP_QUERY_RFC3986),
        $this->secretAccessKey,
        true
      )
    );
  }

  /**
   * If the given value is not an array, wrap it in one.
   *
   * @param  mixed  $value
   * @return array
   */
  private function wrap($value) {
    return !is_array($value) ? [$value] : $value;
  }

  /**
   * Used to format the header into an array with key=>value
   *
   * @param array $headers
   * @return array
   */
  private function formatHeader($headers = []) {
    $arrHeader = [];
    foreach (explode("\r\n", trim((string)$headers)) as $header) {
      if (preg_match('/(.*?): (.*)/', $header, $matches)) {
        $arrHeader[$matches[1]] = $matches[2];
      } else {
        $arrHeader['Http-Code'] = $header;
      }
    }

    // Return the header transformed to array
    return $arrHeader;
  }

  /**
   * @param string $action
   * @param string $response
   * @return array
   */
  private function formatResponse(string $action, string $response) {
    $response = str_replace("ns2:","", $response);
    $responseXML = simplexml_load_string(trim($response), "SimpleXMLElement", LIBXML_NOCDATA);
    return json_decode(json_encode($responseXML->{$action . 'Result'}), true);
  }
}