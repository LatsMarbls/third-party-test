<?php
require_once __DIR__ . '/w3p_client.php';

$w3p = new W3PSoapClient();
$id = $w3p->getW3pId();
$key = $w3p->getW3pKey();

echo "============================================\n";
echo "  W3P - Alliance WebPOS SOAP Test Suite\n";
echo "  Server: statara2.alliancewebpos.net\n";
echo "============================================\n\n";

$tests = [];

$tests[] = ['GET_ACCOUNT', "<root>
  <id>
    <fw3p_id>{$id}</fw3p_id>
    <fw3p_key>{$key}</fw3p_key>
  </id>
  <data>
    <filter>
      <fkeyword></fkeyword>
    </filter>
  </data>
</root>"];

$tests[] = ['GET_PRODUCT', "<root>
  <id>
    <fw3p_id>{$id}</fw3p_id>
    <fw3p_key>{$key}</fw3p_key>
  </id>
  <data>
    <filter>
      <fkeyword></fkeyword>
    </filter>
  </data>
</root>"];

$tests[] = ['GET_TRANSACTION', "<root>
  <id>
    <fw3p_id>{$id}</fw3p_id>
    <fw3p_key>{$key}</fw3p_key>
  </id>
  <data>
    <filter>
      <fkeyword></fkeyword>
    </filter>
  </data>
</root>"];

$tests[] = ['GET_LOYALTY_MEMBER', "<root>
  <id>
    <fw3p_id>{$id}</fw3p_id>
    <fw3p_key>{$key}</fw3p_key>
  </id>
  <data>
    <filter>
      <ffspmembid>6704-3865</ffspmembid>
      <faccountid></faccountid>
      <ffmember_date></ffmember_date>
      <ftmember_date></ftmember_date>
      <ffcreated_date></ffcreated_date>
      <ftcreated_date></ftcreated_date>
      <fffsp_expiry></fffsp_expiry>
      <fftsp_expiry></fftsp_expiry>
      <ffsp_status_flag>1</ffsp_status_flag>
      <fofficeid>TEST</fofficeid>
      <fnew_batchid></fnew_batchid>
      <flast_batchid></flast_batchid>
      <flast_key></flast_key>
    </filter>
  </data>
</root>"];

$tests[] = ['GET_LOYALTY_POINT_BALANCE', "<root>
  <id>
    <fw3p_id>{$id}</fw3p_id>
    <fw3p_key>{$key}</fw3p_key>
  </id>
  <data>
    <record>
      <fofficeid>TEST</fofficeid>
      <ffspmembid>6704-3865</ffspmembid>
      <ffsp_status_flag></ffsp_status_flag>
      <fkeyword></fkeyword>
    </record>
  </data>
</root>"];

$tests[] = ['GET_LOYALTY_POINT_USAGE', "<root>
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
</root>"];

$tests[] = ['GET_LOYALTY_TRANSACTION_SUMMARY', "<root>
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
</root>"];

$tests[] = ['GET_LOYALTY_LEDGER', "<root>
  <id>
    <fw3p_id>{$id}</fw3p_id>
    <fw3p_key>{$key}</fw3p_key>
  </id>
  <data>
    <filter>
      <ffspmembid>6704-3865</ffspmembid>
      <fftrxdate></fftrxdate>
      <fttrxdate></fttrxdate>
    </filter>
  </data>
</root>"];

$tests[] = ['GET_LOYALTY_ADJUST_POINT', "<root>
  <id>
    <fw3p_id>{$id}</fw3p_id>
    <fw3p_key>{$key}</fw3p_key>
  </id>
  <data>
    <filter>
      <ffspmembid>6704-3865</ffspmembid>
      <fstatus_flag>6</fstatus_flag>
      <fftrxdate></fftrxdate>
      <fttrxdate></fttrxdate>
      <freference_code></freference_code>
      <fdocument_no></fdocument_no>
    </filter>
  </data>
</root>"];

$tests[] = ['SAVE_LOYALTY_ADJUST_POINT', "<root>
  <id>
    <fw3p_id>{$id}</fw3p_id>
    <fw3p_key>{$key}</fw3p_key>
  </id>
  <data>
    <record>
      <ftrxdate>20231201</ftrxdate>
      <freference_code>1</freference_code>
      <ffspmembid>6704-3865</ffspmembid>
      <fofficeid>TEST</fofficeid>
      <fpoint>1</fpoint>
      <fmemo>Point adjustment from Doxo</fmemo>
    </record>
  </data>
</root>"];

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
    } else {
        $failed++;
        echo "      Error: {$result['error']}\n";
    }

    echo "--- Request XML ---\n{$result['request_xml']}\n";
    echo "--- Response ---\n{$result['response']}\n\n";
}

echo "\n============================================\n";
echo "  Results: {$passed} passed, {$failed} failed\n";
echo "============================================\n";
