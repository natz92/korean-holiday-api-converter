<?php
require_once 'vendor/autoload.php';

class Convert
{
    const SERVICE_URL = "http://apis.data.go.kr/B090041/openapi/service/SpcdeInfoService/getHoliDeInfo";

    private $serviceKey;
    private $year;

    public function __construct(String $serviceKey, String $year)
    {
        $this->year = $year;
        $this->serviceKey = $serviceKey;

        $this->get();
    }

    private function get()
    {

        $client = new \GuzzleHttp\Client();

        $month = 1;

        $text = "";

        while (true) {

            $solMonth = $month < 10 ? (int) '0' . $month : $month;
            $response = $client->request('GET', self::SERVICE_URL . "?ServiceKey={$this->serviceKey}&solYear={$this->year}&solMonth={$solMonth}");

            $xml = simplexml_load_string($response->getBody()->getContents());

            foreach ($xml->body->items->item as $item) {

                $text .= date('Y-m-d', strtotime($item->locdate)) . ":\n";
                $text .= "\tname: '{$item->dateName}'\n";
                $text .= "\tmonth: '{$solMonth}'\n";
                $text .= "\tday: '" . date('d', strtotime($item->locdate)) . "'\n";
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

$script = new Convert($argv[1], $argv[2]);