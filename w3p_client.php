<?php

class W3PSoapClient
{
    private SoapClient $client;
    private string $w3p_id;
    private string $w3p_key;

    public function __construct(string $w3p_id = '', string $w3p_key = '')
    {
        $this->w3p_id = $w3p_id ?: getenv('W3P_ID') ?: '';
        $this->w3p_key = $w3p_key ?: getenv('W3P_KEY') ?: '';

        $wsdl = getenv('W3P_WSDL') ?: 'http://statara2.alliancewebpos.net/appserv/app/w3p/w3p.wsdl';
        $endpoint = getenv('W3P_ENDPOINT') ?: 'http://statara2.alliancewebpos.net/appserv/app/w3p/W3PSoapServer.php';

        $this->client = new SoapClient($wsdl, [
            'location' => $endpoint,
            'trace' => true,
            'exceptions' => true,
            'connection_timeout' => 30,
        ]);
    }

    public function call(string $action, string $params): string
    {
        $response = $this->client->call($action, $params);
        return $response;
    }

    public function buildIdBlock(): string
    {
        return "<id>
    <fw3p_id>{$this->w3p_id}</fw3p_id>
    <fw3p_key>{$this->w3p_key}</fw3p_key>
  </id>";
    }

    public function buildFilterParams(string $action, array $filterFields): string
    {
        $filter = '';
        foreach ($filterFields as $key => $value) {
            $filter .= "      <{$key}>{$value}</{$key}>\n";
        }

        return "<root>
  {$this->buildIdBlock()}
  <data>
    <filter>
{$filter}    </filter>
  </data>
</root>";
    }

    public function buildRecordParams(string $action, array $recordFields): string
    {
        $record = '';
        foreach ($recordFields as $key => $value) {
            $record .= "      <{$key}>{$value}</{$key}>\n";
        }

        return "<root>
  {$this->buildIdBlock()}
  <data>
    <record>
{$record}    </record>
  </data>
</root>";
    }

    public function __call(string $action, array $params)
    {
        return $this->call($action, $params[0] ?? '');
    }

    public function getLastRequest(): string
    {
        return $this->client->__getLastRequest();
    }

    public function getLastResponse(): string
    {
        return $this->client->__getLastResponse();
    }

    public function runTest(string $action, string $params): array
    {
        $start = microtime(true);

        try {
            $response = $this->call($action, $params);
            $time = round((microtime(true) - $start) * 1000);

            return [
                'action' => $action,
                'success' => true,
                'time_ms' => $time,
                'response' => $response,
                'request_xml' => $this->getLastRequest(),
                'response_xml' => $this->getLastResponse(),
                'error' => null,
            ];
        } catch (SoapFault $e) {
            $time = round((microtime(true) - $start) * 1000);

            return [
                'action' => $action,
                'success' => false,
                'time_ms' => $time,
                'response' => null,
                'request_xml' => $this->getLastRequest(),
                'response_xml' => $this->getLastResponse(),
                'error' => $e->getMessage(),
            ];
        }
    }
}
