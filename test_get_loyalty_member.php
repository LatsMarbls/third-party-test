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
    <filter>
      <ffspmembid>6704-3865</ffspmembid>
      <ffsp_status_flag>1</ffsp_status_flag>
      <fofficeid>TEST</fofficeid>
    </filter>
  </data>
</root>";

echo "--- Request ---\n" . $p . "\n\n";

$s = $client->call("GET_LOYALTY_MEMBER", $p);

echo "--- Response ---\n" . $s . "\n";
