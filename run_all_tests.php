<?php
ini_set("soap.wsdl_cache_enabled", "0");
require_once __DIR__ . '/config.php';

$client = new SoapClient($wsdl, array("location" => $endpoint));

$tests[] = ["GET_ACCOUNT", "<root>
  <id>
    <fw3p_id>$w3p_id</fw3p_id>
    <fw3p_key>$w3p_key</fw3p_key>
  </id>
  <data>
    <filter>
      <faccountid>WEBPOS</faccountid>
    </filter>
  </data>
</root>"];

$tests[] = ["GET_PRODUCT", "<root>
  <id>
    <fw3p_id>$w3p_id</fw3p_id>
    <fw3p_key>$w3p_key</fw3p_key>
  </id>
  <data>
    <filter>
      <fkeyword></fkeyword>
    </filter>
  </data>
</root>"];

$tests[] = ["GET_TRANSACTION", "<root>
  <id>
    <fw3p_id>$w3p_id</fw3p_id>
    <fw3p_key>$w3p_key</fw3p_key>
  </id>
  <data>
    <filter>
      <fkeyword></fkeyword>
    </filter>
  </data>
</root>"];

$tests[] = ["GET_LOYALTY_MEMBER", "<root>
  <id>
    <fw3p_id>$w3p_id</fw3p_id>
    <fw3p_key>$w3p_key</fw3p_key>
  </id>
  <data>
    <filter>
      <ffspmembid>6704-3865</ffspmembid>
      <ffsp_status_flag>1</ffsp_status_flag>
      <fofficeid>TEST</fofficeid>
    </filter>
  </data>
</root>"];

$tests[] = ["GET_LOYALTY_POINT_BALANCE", "<root>
  <id>
    <fw3p_id>$w3p_id</fw3p_id>
    <fw3p_key>$w3p_key</fw3p_key>
  </id>
  <data>
    <record>
      <ffspmembid>6704-3865</ffspmembid>
      <fofficeid>TEST</fofficeid>
    </record>
  </data>
</root>"];

$tests[] = ["GET_LOYALTY_POINT_USAGE", "<root>
  <id>
    <fw3p_id>$w3p_id</fw3p_id>
    <fw3p_key>$w3p_key</fw3p_key>
  </id>
  <data>
    <record>
      <ffspmembid>6704-3865</ffspmembid>
      <fofficeid>TEST</fofficeid>
    </record>
  </data>
</root>"];

$tests[] = ["GET_LOYALTY_TRANSACTION_SUMMARY", "<root>
  <id>
    <fw3p_id>$w3p_id</fw3p_id>
    <fw3p_key>$w3p_key</fw3p_key>
  </id>
  <data>
    <record>
      <ffspmembid>6704-3865</ffspmembid>
      <fofficeid>TEST</fofficeid>
    </record>
  </data>
</root>"];

$tests[] = ["GET_LOYALTY_LEDGER", "<root>
  <id>
    <fw3p_id>$w3p_id</fw3p_id>
    <fw3p_key>$w3p_key</fw3p_key>
  </id>
  <data>
    <filter>
      <ffspmembid>6704-3865</ffspmembid>
    </filter>
  </data>
</root>"];

$tests[] = ["GET_LOYALTY_ADJUST_POINT", "<root>
  <id>
    <fw3p_id>$w3p_id</fw3p_id>
    <fw3p_key>$w3p_key</fw3p_key>
  </id>
  <data>
    <filter>
      <ffspmembid>6704-3865</ffspmembid>
    </filter>
  </data>
</root>"];

$tests[] = ["SAVE_LOYALTY_ADJUST_POINT", "<root>
  <id>
    <fw3p_id>$w3p_id</fw3p_id>
    <fw3p_key>$w3p_key</fw3p_key>
  </id>
  <data>
    <record>
      <ffspmembid>6704-3865</ffspmembid>
      <fofficeid>TEST</fofficeid>
      <ftrxdate>20231201</ftrxdate>
      <freference_code>1</freference_code>
      <fpoint>1</fpoint>
      <fmemo>Point adjustment from Doxo</fmemo>
    </record>
  </data>
</root>"];

echo "============================================\n";
echo "  W3P - Alliance WebPOS SOAP Test Suite\n";
echo "  Server: " . str_replace("http://", "", $endpoint) . "\n";
echo "============================================\n\n";

foreach ($tests as $test) {
    echo "--- " . $test[0] . " ---\n";
    echo "\nRequest:\n" . $test[1] . "\n";

    $s = $client->call($test[0], $test[1]);

    echo "\nResponse:\n" . $s . "\n\n";
}
