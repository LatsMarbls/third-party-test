<?php
require_once __DIR__ . '/w3p_client.php';

$w3p = new W3PSoapClient();
$id = $w3p->getW3pId();
$key = $w3p->getW3pKey();

$xml = "<root>
  <id>
    <fw3p_id>{$id}</fw3p_id>
    <fw3p_key>{$key}</fw3p_key>
  </id>
  <data>
    <record>
      <fftrxdate></fftrxdate>
      <fttrxdate></fttrxdate>
      <fdoctype></fdoctype>
      <freference_code></freference_code>
      <fofficeid>TEST</fofficeid>
      <ffspmembid>6704-3865</ffspmembid>
      <fkeyword></fkeyword>
    </record>
  </data>
</root>";

$result = $w3p->runTest('GET_LOYALTY_POINT_USAGE', $xml);

echo "=== GET_LOYALTY_POINT_USAGE (6704-3865) ===\n";
echo "Time: {$result['time_ms']}ms\n";
echo "Status: " . ($result['success'] ? 'OK' : 'FAIL') . "\n";
if ($result['error']) {
    echo "Error: {$result['error']}\n";
}
echo "\n--- Request XML ---\n{$result['request_xml']}\n";
echo "\n--- Response ---\n{$result['response']}\n";
