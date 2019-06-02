<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Analyzer
 * @package App
 */
class Analyzer extends Model
{
    protected $webUrl;
    protected $result = [
        'valid_url' => false,

        'status_code' => 0,
        'gzip_supported' => false,
        'http2_supported' => false,
        'webp_supported' => false,

        'typed_alttag' => true,

        'robotstxt_indexing' => false,
        'robotsmetatag_indexing' => true,
        'xrobotstag_indexing' => true,

        'google_insights' => [
            'first_contentful_paint',
            'speed_index',
            'time_to_interactive',
            'first_meaningful_paint',
            'first_cpu_idle',
            'estimated_input_latency'
        ]
    ];

    /**
     * Nastaví URL
     *
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->webUrl = $url;
    }

    /**
     * Nastaví podporu webp/image (kontrola zda prohlížeč podporuje webp probíhá v JS)
     *
     * @param string $supported
     */
    public function setWebpSupport(string $supported)
    {
        $this->result['webp_supported'] = ($supported == 'true') ? true : false;
    }

    /**
     * Zanalyzuje header webu (status kód, podpora GZIP, podpora HTTP2, podpora image/webp, x-robots-tag indexace)
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function analyzeHeader()
    {
        $client = new Client();
        $response = $client->request('GET', $this->webUrl, [
            'decode_content' => false,
            'version' => 2.0,
            'timeout' => 5.0,
        ]);

        $this->result['status_code'] = $response->getStatusCode();
        $this->result['gzip_supported'] = (strpos(request()->server('HTTP_ACCEPT_ENCODING'), 'gzip') !== false) ? true : false;
        $this->result['http2_supported'] = ($response->getProtocolVersion() == '2') ? true : false;
        //$this->result['webp_supported'] = (strpos(request()->server('HTTP_ACCEPT'), 'image/webp') !== false) ? true : false;

        // X-Robots-Tag
        $xRobotTag = str_replace(' ', '', $response->getHeaderLine('x-robots-tag'));
        $xRobotTag_array = explode(",", $xRobotTag);
        if(in_array('noindex', $xRobotTag_array) || in_array('none', $xRobotTag_array))
        {
            $this->result['xrobotstag_indexing'] = false;
        }
    }

    /**
     * Znalyzuje obsah webu (zda img mají vyplněný ALT tag, meta tag indexace, existence robots.txt)
     */
    public function analyzeContent()
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML(file_get_contents($this->webUrl));

        // ALT tag img
        $images = $dom->getElementsByTagName("img");
        foreach($images as $image)
        {
            if(!$image->hasAttribute("alt"))
            {
                $this->result['typed_alttag'] = false;
                break;
            }
        }

        // Meta tag robots
        $metas = $dom->getElementsByTagName('meta');
        foreach($metas as $meta)
        {
            if($meta->getAttribute('name') == 'robots')
            {
                $metaTagRobots = str_replace(' ', '', $meta->getAttribute('content'));
                $metaTagRobots_array = explode(",", $metaTagRobots);
                if(in_array('noindex', $metaTagRobots_array) || in_array('none', $metaTagRobots_array))
                {
                    $this->result['robotsmetatag_indexing'] = false;
                    break;
                }
            }
        }

        // robots.txt
        $this->result['robotstxt_indexing'] = ($this->remoteFileExists($this->webUrl.'/robots.txt')) ? true : false;
    }

    /**
     * Kontrola zda je URL validní
     *
     * @param string $url
     * @return bool
     */
    public function isUrlValidate(string $url): bool
    {
        if(filter_var($url, FILTER_VALIDATE_URL))
        {
            $this->result['valid_url'] = true;
            return true;
        }
        return false;
    }

    /**
     * Kontrola zda vzdálený soubor existuje
     *
     * @param string $url
     * @return bool
     */
    protected function remoteFileExists(string $url): bool
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        $result = curl_exec($curl);
        $ret = false;

        if ($result !== false)
        {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($statusCode == 200)
            {
                $ret = true;
            }
        }
        curl_close($curl);

        return $ret;
    }

    /**
     * Scan URL přes Google API Insights
     */
    public function scanPageSpeed()
    {
        $url = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url='.$this->webUrl;

        $ch = curl_init();
        $timeout = 60;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $audits = $result['lighthouseResult']['audits'];

        $this->result['google_insights']['first_contentful_paint'] = $audits['first-contentful-paint']['displayValue'];
        $this->result['google_insights']['speed_index'] = $audits['speed-index']['displayValue'];
        $this->result['google_insights']['time_to_interactive'] = $audits['interactive']['displayValue'];
        $this->result['google_insights']['first_meaningful_paint'] = $audits['first-meaningful-paint']['displayValue'];
        $this->result['google_insights']['first_cpu_idle'] = $audits['first-cpu-idle']['displayValue'];
        $this->result['google_insights']['estimated_input_latency'] = $audits['estimated-input-latency']['displayValue'];
    }

    /**
     * Vrátí výsledek celkové analýzy webové stránky
     *
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }
}
