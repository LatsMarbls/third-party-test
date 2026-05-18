<?php
require_once __DIR__ . '/w3p_client.php';

$testMemberId = getenv('W3P_TEST_MEMBER_ID') ?: 'PLACEHOLDER_MEMBER_ID';
$w3p = new W3PSoapClient(getenv('W3P_ID'), getenv('W3P_KEY'));

$xml = $w3p->buildRecordParams('SAVE_LOYALTY_ADJUST_POINT', [
    'ftrxdate' => '20231201',
    'freference_code' => '1',
    'ffspmembid' => $testMemberId,
    'fofficeid' => 'TEST',
    'fpoint' => '1',
    'fmemo' => 'Point adjustment from Doxo',
]);

$result = $w3p->runTest('SAVE_LOYALTY_ADJUST_POINT', $xml);

echo "=== SAVE_LOYALTY_ADJUST_POINT ===\n";
echo "Time: {$result['time_ms']}ms\n";
echo "Status: " . ($result['success'] ? 'OK' : 'FAIL') . "\n";
if ($result['error']) {
    echo "Error: {$result['error']}\n";
}
echo "\n--- Request XML ---\n{$result['request_xml']}\n";
echo "\n--- Response ---\n{$result['response']}\n";
