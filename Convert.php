<?php
require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Convert
{
    const SERVICE_URL = "http://apis.data.go.kr/B090041/openapi/service/SpcdeInfoService/getHoliDeInfo";

    private $serviceKey;
    private $year;

    /**
     * Convert constructor.
     * @param String $serviceKey
     * @param String $year
     * @throws Exception
     */
    public function __construct(String $serviceKey = null, String $year = null)
    {
        $this->year = $year;
        $this->serviceKey = $serviceKey;

        $this->validate();
        $this->get();
    }

    /**
     * @throws Exception
     */
    private function validate()
    {
        if (empty($this->serviceKey)) {
            throw new Exception('API 서비스키가 필요합니다');
        }

        if ($this->year < 2015) {
            throw new Exception('2015년 이후 데이터부터 지원합니다');
        }
    }

    private function get()
    {

        $client = new Client();

        $month = 1;
        $text = "";

        while (true) {

            $solMonth = $month < 10 ? (int) '0' . $month : $month;
            try {
                $response = $client->request('GET', self::SERVICE_URL . "?ServiceKey={$this->serviceKey}&solYear={$this->year}&solMonth={$solMonth}");
            } catch (GuzzleException $e) {
                echo $e->getMessage();
            }

            $xml = simplexml_load_string($response->getBody()->getContents());

            $solMonth = (int) $solMonth;
            foreach ($xml->body->items->item as $item) {

                $text .= "'" . date('Y-m-d', strtotime($item->locdate)) . "':\n";
                $text .= "  name: {$item->dateName}\n";
                $text .= "  month: '{$solMonth}'\n";
                $text .= "  day: '" . date('j', strtotime($item->locdate)) . "'\n\n";
            }

            if ($month++ == 12) {
                break;
            }

        }

        $dir = "result";
        if (!is_dir($dir)) {
            mkdir($dir, '0777');
        }

        $file = fopen('result/' . $this->year . '.yml', "w");

        fwrite($file, $text);

        echo $this->year . "년 생성 완료";
    }

}

try {
    $script = new Convert($argv[1], $argv[2]);
} catch (Exception $e) {
    echo $e->getMessage();
}