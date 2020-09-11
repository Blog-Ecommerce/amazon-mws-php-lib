<?php

namespace CapsuleB\AmazonMWS\Resources;

use CapsuleB\AmazonMWS\Resource;
use Exception;

/**
 * Class Products
 * @package CapsuleB\AmazonMWS\Resources
 * @see https://docs.developer.amazonservices.com/en_US/products/Products_Overview.html
 */
class Products extends Resource {

  const BASE = [
    'path'    => '/Products/2011-10-01',
    'version' => '2011-10-01'
  ];

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_ListMatchingProducts.html
   * @param string $query
   * @param string|null $queryContextId
   * @return array|mixed|object
   * @throws Exception
   */
  public function listMatchingProducts(string $query, string $queryContextId = null) {
    return $this->client->post(self::BASE, 'ListMatchingProducts', [
      'Query' => urlencode(trim($query)),
      'QueryContextId' => $queryContextId
    ]);
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetMatchingProduct.html
   * @param array $ASINList
   * @return array|mixed|object
   * @throws Exception
   */
  public function getMatchingProduct(array $ASINList) {
    return $this->client->post(self::BASE, 'GetMatchingProduct',
      $this->arrayToList(['ASINList' => ['ASIN' => $ASINList]])
    );
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetMatchingProductForId.html
   * @param string $idType
   * @param array $idList
   * @return array|mixed|object
   * @throws Exception
   */
  public function getMatchingProductForId(string $idType, array $idList) {
    return $this->client->post(self::BASE, 'GetMatchingProductForId', array_merge(
      ['IdType' => $idType],
      $this->arrayToList(['IdList' => ['Id' => $idList]])
    ));
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetCompetitivePricingForSKU.html
   * @param array $sellerSKUList
   * @return array|mixed|object
   * @throws Exception
   */
  public function getCompetitivePricingForSKU(array $sellerSKUList) {
    return $this->client->post(self::BASE, 'GetCompetitivePricingForSKU',
      $this->arrayToList(['SellerSKUList' => ['SellerSKU' => $sellerSKUList]])
    );
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetCompetitivePricingForASIN.html
   * @param array $ASINList
   * @return array|mixed|object
   * @throws Exception
   */
  public function getCompetitivePricingForASIN(array $ASINList) {
    return $this->client->post(self::BASE, 'GetCompetitivePricingForASIN',
      $this->arrayToList(['ASINList' => ['ASIN' => $ASINList]])
    );
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetLowestOfferListingsForSKU.html
   * @param array $sellerSKUList
   * @param string $itemCondition
   * @return array|mixed|object
   * @throws Exception
   */
  public function getLowestOfferListingsForSKU(array $sellerSKUList, string $itemCondition) {
    return $this->client->post(self::BASE, 'GetLowestOfferListingsForSKU', array_merge(
      ['ItemCondition' => $itemCondition],
      $this->arrayToList(['SellerSKUList' => ['SellerSKU' => $sellerSKUList]])
    ));
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetLowestOfferListingsForASIN.html
   * @param array $ASINList
   * @param string $itemCondition
   * @return array|mixed|object
   * @throws Exception
   */
  public function getLowestOfferListingsForASIN(array $ASINList, string $itemCondition) {
    return $this->client->post(self::BASE, 'GetLowestOfferListingsForASIN', array_merge(
      ['ItemCondition' => $itemCondition],
      $this->arrayToList(['ASINList' => ['ASIN' => $ASINList]])
    ));
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetLowestPricedOffersForSKU.html
   * @param string $sellerSKU
   * @param string $itemCondition
   * @return array|mixed|object
   * @throws Exception
   */
  public function getLowestPricedOffersForSKU(string $sellerSKU, string $itemCondition) {
    return $this->client->post(self::BASE, 'GetLowestPricedOffersForSKU', [
      'SellerSKU'     => $sellerSKU,
      'ItemCondition' => $itemCondition
    ]);
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetLowestPricedOffersForASIN.html
   * @param string $ASIN
   * @param string $itemCondition
   * @return array|mixed|object
   * @throws Exception
   */
  public function getLowestPricedOffersForASIN(string $ASIN, string $itemCondition) {
    return $this->client->post(self::BASE, 'GetLowestPricedOffersForASIN', [
      'ASIN'          => $ASIN,
      'ItemCondition' => $itemCondition
    ]);
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetMyFeesEstimate.html
   * @param array $feesEstimateRequestList
   * @return array|mixed|object
   * @throws Exception
   */
  public function getMyFeesEstimate(array $feesEstimateRequestList) {
    return $this->client->post(self::BASE, 'GetMyFeesEstimate',
      $this->arrayToList(['FeesEstimateRequestList' => ['FeesEstimateRequest' => $feesEstimateRequestList]])
    );
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetMyPriceForSKU.html
   * @param string $sellerSKU
   * @param string $itemCondition
   * @return array|mixed|object
   * @throws Exception
   */
  public function getMyPriceForSKU(string $sellerSKU, string $itemCondition) {
    return $this->client->post(self::BASE, 'GetMyPriceForSKU', [
      'SellerSKU'     => $sellerSKU,
      'ItemCondition' => $itemCondition
    ]);
}

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetMyPriceForASIN.html
   * @param array $ASINList
   * @param string $itemCondition
   * @return array|mixed|object
   * @throws Exception
   */
  public function getMyPriceForASIN(array $ASINList, string $itemCondition) {
    return $this->client->post(self::BASE, 'GetMyPriceForASIN', array_merge(
      ['ItemCondition' => $itemCondition],
      $this->arrayToList(['ASINList' => ['ASIN' => $ASINList]])
    ));
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetProductCategoriesForSKU.html
   * @param string $sellerSKU
   * @return array|mixed|object
   * @throws Exception
   */
  public function getProductCategoriesForSKU(string $sellerSKU) {
    return $this->client->post(self::BASE, 'GetProductCategoriesForSKU', [
      'SellerSKU' => $sellerSKU
    ]);
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetProductCategoriesForASIN.html
   * @param string $ASIN
   * @return array|mixed|object
   * @throws Exception
   */
  public function getProductCategoriesForASIN(string $ASIN) {
    return $this->client->post(self::BASE, 'GetProductCategoriesForASIN', [
      'ASIN' => $ASIN
    ]);
  }

  /**
   * @see https://docs.developer.amazonservices.com/en_US/products/Products_GetServiceStatus.html
   * @return array|mixed|object
   * @throws Exception
   */
  public function getServiceStatus() {
    return $this->client->post(self::BASE, 'GetServiceStatus');
  }

}