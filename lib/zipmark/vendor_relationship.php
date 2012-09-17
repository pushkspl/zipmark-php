<?php

class Zipmark_VendorRelationship extends Zipmark_Resource {
  /**
   * Find all vendor relationships
   *
   * @param  string                         $params Parameters for find
   * @param  Zipmark_Client                 $client Client object to connect to service
   *
   * @return Zipmark_VendorRelationships            A list of Disbursements
   */
  public static function all($params = null, $client = null) {
    $list = new Zipmark_VendorRelationships(Zipmark_Client::PATH_VENDOR_RELATIONSHIPS, $client);
    $list->_loadFrom(Zipmark_Client::PATH_VENDOR_RELATIONSHIPS, $params);
    return $list;
  }
}

?>
