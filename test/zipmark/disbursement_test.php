<?php

class ZipmarkDisbursementTest extends UnitTestCase {
  function testDisbursementGet() {
    $response = loadFixture('disbursements/get.http');

    $http = new MockZipmark_Http();
    $http->returns('GET', $response, array('/disbursements/b1a42f3e-bf21-4651-8ca4-d716593277db', null));

    $client = new Zipmark_Client(null, null, false, null, $http);

    $disbursement = $client->disbursements->get('b1a42f3e-bf21-4651-8ca4-d716593277db');

    $this->assertIsA($disbursement, 'Zipmark_Disbursement');
    $this->assertEqual($disbursement->getHref(), 'http://example.org/disbursements/b1a42f3e-bf21-4651-8ca4-d716593277db');
    $this->assertEqual($disbursement->id, 'b1a42f3e-bf21-4651-8ca4-d716593277db');
    $this->assertEqual($disbursement->customer_id, "Customer ID");
    $this->assertEqual($disbursement->status, 'pending');
    $this->assertEqual($disbursement->amount_cents, 5000);
  }

  function testDisbursementCreate() {
    $response = loadFixture('disbursements/create.http');

    $disbursement_data = array(
      'user_email'   => 'test@example.com',
      'customer_id'  => 'abc123',
      'amount_cents' => 5000,
    );

    $disbursement_json = json_encode(array("disbursement" => $disbursement_data));
    
    $http = new MockZipmark_Http();
    $http->returns('POST', $response, array('/disbursements', $disbursement_json));

    $client = new Zipmark_Client(null, null, false, null, $http);

    $disbursement = $client->disbursements->create($disbursement_data);

    $this->assertIsA($disbursement, 'Zipmark_Disbursement');
    $this->assertEqual($disbursement->id, 'eafe7f6e-22b3-453e-9637-a482e2a144da');
    $this->assertEqual($disbursement->customer_id, 'abc123');
    $this->assertEqual($disbursement->status, 'pending');
    $this->assertEqual($disbursement->amount_cents, 5000);
  }

  function testDisbursementCreateFail() {
    $response = loadFixture('disbursements/create_fail.http');

    $disbursement_data = array(
      'user_email'   => 'test@example.com',
      'customer_id'  => 'abc123',
    );

    $disbursement_json = json_encode(array("disbursement" => $disbursement_data));
    
    $http = new MockZipmark_Http();
    $http->returns('POST', $response, array('/disbursements', $disbursement_json));

    $client = new Zipmark_Client(null, null, false, null, $http);

    try {
      $client->disbursements->create($disbursement_data);
      $this->fail("Expected Zipmark_ValidationError");
    }
    catch (Zipmark_ValidationError $e) {
      $this->assertEqual($e->getMessage(), "disbursement - amount_cents: can't be blank");
      $this->pass("Received Zipmark_ValidationError");
    }

    $this->assertEqual($response->statusCode, 422);
  }

  function testDisbursementToJson() {
    $disbursement = new Zipmark_Disbursement();
    $disbursement->customer_id = 'abc123';
    $disbursement->amount_cents = 5000;
    
    $json = $disbursement->toJson();
    $this->assertEqual($json, '{"disbursement":{"customer_id":"abc123","amount_cents":5000}}');
  }
}

?>
