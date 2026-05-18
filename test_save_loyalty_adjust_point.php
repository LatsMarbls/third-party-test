<?php
ini_set("soap.wsdl_cache_enabled", "0");
require_once __DIR__ . '/config.php';

$client = new SoapClient($wsdl, array("location" => $endpoint));

$p = "<root>
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
</root>";

echo "--- Request ---\n" . $p . "\n\n";

$s = $client->call("SAVE_LOYALTY_ADJUST_POINT", $p);

echo "--- Response ---\n" . $s . "\n";
