<?php

$wsdl = getenv('W3P_WSDL') ?: 'http://statara2.alliancewebpos.net/appserv/app/w3p/w3p.wsdl';
$endpoint = getenv('W3P_ENDPOINT') ?: 'http://statara2.alliancewebpos.net/appserv/app/w3p/W3PSoapServer.php';
$w3p_id = getenv('W3P_ID');
$w3p_key = getenv('W3P_KEY');
$testMemberId = getenv('W3P_TEST_MEMBER_ID') ?: 'PLACEHOLDER_MEMBER_ID';

$tests = [
    'GET_ACCOUNT' => [$w3p_id, $w3p_key, 'filter', ['fkeyword' => '']],
    'GET_LOYALTY_MEMBER' => [$w3p_id, $w3p_key, 'filter', [
        'ffspmembid' => $testMemberId,
        'ffsp_status_flag' => '1',
        'fofficeid' => 'TEST'
    ]],
    'GET_LOYALTY_POINT_BALANCE' => [$w3p_id, $w3p_key, 'record', [
        'ffspmembid' => $testMemberId,
        'fofficeid' => 'TEST'
    ]],
];

try {
    $client = new SoapClient($wsdl, [
        'location' => $endpoint,
        'trace' => true,
        'exceptions' => true,
        'connection_timeout' => 30,
    ]);

    echo "=== Connected to: {$endpoint}\n\n";

    foreach ($tests as $action => $params) {
        [$id, $key, $dataType, $fields] = $params;
        echo "--- Testing: {$action} ---\n";

        try {
            $response = $client->call($action, buildParams($id, $key, $dataType, $fields));

            echo "Request XML:\n";
            echo $client->__getLastRequest() . "\n\n";

            echo "Response:\n";
            print_r($response);
            echo "\n\n";
        } catch (SoapFault $e) {
            echo "SOAP Fault: {$e->getMessage()}\n";
            echo "Request XML:\n";
            echo $client->__getLastRequest() . "\n\n";
            echo "Response XML:\n";
            echo $client->__getLastResponse() . "\n\n";
        }
    }
} catch (Exception $e) {
    echo "Connection Error: {$e->getMessage()}\n";
}

function buildParams(string $w3p_id, string $w3p_key, string $dataType, array $fields): string
{
    $dataFields = '';
    foreach ($fields as $key => $value) {
        $dataFields .= "<{$key}>{$value}</{$key}>\n";
    }

    return "<root>
  <id>
    <fw3p_id>{$w3p_id}</fw3p_id>
    <fw3p_key>{$w3p_key}</fw3p_key>
  </id>
  <data>
    <{$dataType}>
      {$dataFields}
    </{$dataType}>
  </data>
</root>";
}
