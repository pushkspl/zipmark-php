<?php

class ZipmarkApprovalRuleTest extends UnitTestCase {
  function testApprovalRuleGet() {
    $response = loadFixture('approval_rules/get.http');

    $http = new MockZipmark_Http();
    $http->returns('GET', $response, array('/approval_rules/9671336a-ee0f-4f98-8e84-b8d221a2b3f3', null));

    $client = new Zipmark_Client(null, null, false, null, $http);

    $approval_rule = $client->approval_rules->get('9671336a-ee0f-4f98-8e84-b8d221a2b3f3');

    $this->assertIsA($approval_rule, 'Zipmark_ApprovalRule');
    $this->assertEqual($approval_rule->getHref(), 'http://example.org/approval_rules/9671336a-ee0f-4f98-8e84-b8d221a2b3f3');
    $this->assertEqual($approval_rule->id, '9671336a-ee0f-4f98-8e84-b8d221a2b3f3');
    $this->assertEqual($approval_rule->period, 'Monthly');
    $this->assertEqual($approval_rule->amount_cents, 10000);
  }

  function testApprovalRuleGetFail() {
    $response = loadFixture('approval_rules/get_fail.http');

    $http = new MockZipmark_Http();
    $http->returns('GET', $response, array('/approval_rules/9671336a-ee0f-4f98-8e84-b8d221a2b3f3', null));

    $client = new Zipmark_Client(null, null, false, null, $http);
    
    try {
      $approval_rule = $client->approval_rules->get('9671336a-ee0f-4f98-8e84-b8d221a2b3f3');
      $this->fail("Expected Zipmark_NotFoundError");
    }
    catch (Zipmark_NotFoundError $e) {
      $this->pass("Received Zipmark_NotFoundError");
    }

    $this->assertEqual($response->statusCode, 404);
  }

  function testApprovalRuleBuildFail() {
    $client = new Zipmark_Client();

    try {
      $approval_rule = $client->approval_rules->build();
      $this->fail("Expected Zipmark_ReadOnlyObjectTypeError");
    }
    catch (Zipmark_ReadOnlyObjectTypeError $e) {
      $this->pass("Received Zipmark_ReadOnlyObjectTypeError");
    }
  }

  function testApprovalRulePath() {
    $approval_rule = new Zipmark_ApprovalRule();
    $path = $approval_rule->pathFor("rule123");

    $this->assertEqual($path, "/approval_rules/rule123");
  }
}

?>
