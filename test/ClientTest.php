<?php

namespace CapsuleB\AmazonMWS\Test;

use CapsuleB\AmazonMWS\Client;
use PHPUnit\Framework\TestCase;
use Exception;

class ClientTest extends TestCase {

  /**
   * @var Client
   */
  private $client;

  protected function setUp(): void {
    parent::setUp();

    // Set the Client
    $this->client = new Client(
      '',
      '',
      '',
      '',
      ''
    );
  }

  /**
   * @throws Exception
   */
  public function testServiceStatus() {
    $this->client->products->getServiceStatus();
  }
}
