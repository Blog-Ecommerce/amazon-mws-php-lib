<?php

namespace CapsuleB\AmazonMWS;

/**
 * Class Resource
 * @package CapsuleB\AmazonMWS
 *
 * @property Client $client
 */
class Resource {

  /**
   * Addons constructor.
   * @param Client $client
   */
  public function __construct(Client $client) {
    $this->client = $client;
  }

  /**
   * Flatten a multi-dimensional associative array with dots.
   *
   * @param iterable $array
   * @param string $prepend
   * @return array
   */
  public function arrayToList(iterable $array, string $prepend = '') {
    $results = [];
    foreach ($array as $key => $value) {
      $keyStr = is_int($key) ? $key + 1 : $key;
      if (is_array($value) && ! empty($value)) {
        $results = array_merge($results, static::arrayToList($value, $prepend . $keyStr . '.'));
      } else {
        $results[$prepend.$keyStr] = $value;
      }
    }

    return $results;
  }

}