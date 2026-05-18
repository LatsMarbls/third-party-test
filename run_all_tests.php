<?php
require_once __DIR__ . '/w3p_client.php';

$w3p_id = getenv('W3P_ID');
$w3p_key = getenv('W3P_KEY');
$testMemberId = getenv('W3P_TEST_MEMBER_ID') ?: 'PLACEHOLDER_MEMBER_ID';

echo "============================================\n";
echo "  W3P - Alliance WebPOS SOAP Test Suite\n";
echo "============================================\n\n";

$w3p = new W3PSoapClient($w3p_id, $w3p_key);

$tests = [];

$tests[] = ['GET_ACCOUNT', $w3p->buildFilterParams('GET_ACCOUNT', ['fkeyword' => ''])];

$tests[] = ['GET_PRODUCT', $w3p->buildFilterParams('GET_PRODUCT', ['fkeyword' => ''])];

$tests[] = ['GET_TRANSACTION', $w3p->buildFilterParams('GET_TRANSACTION', ['fkeyword' => ''])];

$tests[] = ['GET_LOYALTY_MEMBER', $w3p->buildFilterParams('GET_LOYALTY_MEMBER', [
    'ffspmembid' => $testMemberId,
    'faccountid' => '',
    'ffmember_date' => '',
    'ftmember_date' => '',
    'ffcreated_date' => '',
    'ftcreated_date' => '',
    'fffsp_expiry' => '',
    'fftsp_expiry' => '',
    'ffsp_status_flag' => '1',
    'fofficeid' => 'TEST',
    'fnew_batchid' => '',
    'flast_batchid' => '',
    'flast_key' => '',
])];

$tests[] = ['GET_LOYALTY_POINT_BALANCE', $w3p->buildRecordParams('GET_LOYALTY_POINT_BALANCE', [
    'fofficeid' => 'TEST',
    'ffspmembid' => $testMemberId,
    'ffsp_status_flag' => '',
    'fkeyword' => '',
])];

$tests[] = ['GET_LOYALTY_POINT_USAGE', $w3p->buildRecordParams('GET_LOYALTY_POINT_USAGE', [
    'fftrxdate' => '',
    'fttrxdate' => '',
    'fdoctype' => '',
    'freference_code' => '',
    'fofficeid' => 'TEST',
    'ffspmembid' => $testMemberId,
    'fkeyword' => '',
])];

$tests[] = ['GET_LOYALTY_TRANSACTION_SUMMARY', $w3p->buildRecordParams('GET_LOYALTY_TRANSACTION_SUMMARY', [
    'fftrxdate' => '',
    'fttrxdate' => '',
    'fdoctype' => '',
    'freference_code' => '',
    'fofficeid' => 'TEST',
    'ffspmembid' => $testMemberId,
    'fkeyword' => '',
])];

$tests[] = ['GET_LOYALTY_LEDGER', $w3p->buildFilterParams('GET_LOYALTY_LEDGER', [
    'ffspmembid' => $testMemberId,
    'fftrxdate' => '',
    'fttrxdate' => '',
])];

$tests[] = ['GET_LOYALTY_ADJUST_POINT', $w3p->buildFilterParams('GET_LOYALTY_ADJUST_POINT', [
    'ffspmembid' => $testMemberId,
    'fstatus_flag' => '6',
    'fftrxdate' => '',
    'fttrxdate' => '',
    'freference_code' => '',
    'fdocument_no' => '',
])];

$tests[] = ['SAVE_LOYALTY_ADJUST_POINT', $w3p->buildRecordParams('SAVE_LOYALTY_ADJUST_POINT', [
    'ftrxdate' => '20231201',
    'freference_code' => '1',
    'ffspmembid' => $testMemberId,
    'fofficeid' => 'TEST',
    'fpoint' => '1',
    'fmemo' => 'Point adjustment from Doxo',
])];

$passed = 0;
$failed = 0;

foreach ($tests as $i => $test) {
    [$name, $xml] = $test;
    $result = $w3p->runTest($name, $xml);

    $num = str_pad($i + 1, 2, ' ', STR_PAD_LEFT);
    $label = str_pad($name, 45);
    $time = str_pad("{$result['time_ms']}ms", 8, ' ', STR_PAD_LEFT);
    $status = $result['success'] ? 'PASS' : 'FAIL';

    echo "[{$num}] {$label} {$status} {$time}\n";

    if ($result['success']) {
        $passed++;
        echo "      Response: {$result['response']}\n\n";
    } else {
        $failed++;
        echo "      Error: {$result['error']}\n";
        echo "      Response: {$result['response_xml']}\n\n";
    }
}

echo "\n============================================\n";
echo "  Results: {$passed} passed, {$failed} failed\n";
echo "============================================\n";
