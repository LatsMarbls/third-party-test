<?php
require_once __DIR__ . '/w3p_client.php';

$testMemberId = getenv('W3P_TEST_MEMBER_ID') ?: 'PLACEHOLDER_MEMBER_ID';
$w3p = new W3PSoapClient(getenv('W3P_ID'), getenv('W3P_KEY'));

$xml = $w3p->buildRecordParams('GET_LOYALTY_POINT_BALANCE', [
    'fofficeid' => 'TEST',
    'ffspmembid' => $testMemberId,
    'ffsp_status_flag' => '',
    'fkeyword' => '',
]);

$result = $w3p->runTest('GET_LOYALTY_POINT_BALANCE', $xml);

echo "=== GET_LOYALTY_POINT_BALANCE ({$testMemberId}) ===\n";
echo "Time: {$result['time_ms']}ms\n";
echo "Status: " . ($result['success'] ? 'OK' : 'FAIL') . "\n";
if ($result['error']) {
    echo "Error: {$result['error']}\n";
}
echo "\n--- Request XML ---\n{$result['request_xml']}\n";
echo "\n--- Response ---\n{$result['response']}\n";
