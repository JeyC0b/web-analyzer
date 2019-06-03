<?php

namespace NickMoline\Robots;

use NickMoline\Robots\Base as RobotsBase;

class Status extends RobotsBase
{
    protected $originalUrl;
    protected $contents = '';
    protected $statusCode = null;
    protected $responseHeaders = [];
    protected $requestHeaders = [];
    protected $redirects = [];

    public static function createFromExisting(RobotsBase $existing, RobotsBase $robots = null)
    {
        if (!$robots) {
            $robots = new Status();
        }

        $robots = RobotsBase::createFromExisting($existing, $robots);

        if ($existing->isFetched()) {
            $robots->setRequestHeaders($existing->getRequestHeaders())
                   ->setResponseHeaders($existing->getResponseHeaders())
                   ->setStatusCode($existing->getStatusCode())
                   ->setOriginalUrl($existing->getOriginalUrl())
                   ->setRedirects($existing->getRedirects())
                   ->setContents($existing->getContents());
        }

        return $robots;
    }

    public function setContents($content)
    {
        $this->contents = $content;
        return $this;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function setRequestHeaders($headers)
    {
        $this->requestHeaders = $headers;
        return $this;
    }

    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    public function setResponseHeaders($headers)
    {
        $this->responseHeaders = $headers;
        return $this;
    }

    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function validate()
    {
        $this->resetAllowed();
        $curl = $this->checkerCurl();

        if (!$this->isFetched()) {
            $contentFetch = $curl->get($this->url);
            $this->setRequestHeaders($curl->request_headers)
                 ->setResponseHeaders($curl->response_headers)
                 ->setStatusCode($curl->http_status_code)
                 ->setFetched(true);
        }

        if (in_array($this->statusCode, [301, 302, 303, 307])) {
            return $this->processRedirect();
        }

        if (stristr($this->responseHeaders['Content-Type'], "html")) {
            $this->setContents($curl->response);
        }

        return $this->checkAllowedStatusCode();
    }


    private function checkAllowedStatusCode()
    {
        switch ($this->statusCode) {
            case 301:
            case 302:
            case 303:
            case 307:
                $this->label = "Redirected";
                $this->allowed = false;
                $this->reason = "Redirected to {$this->responseHeaders['Location']}";
                return $this->allowed;
            case 401:
                $this->label = "Denied";
                $this->reason = "401: Authentication Required";
                $this->allowed = false;
                return $this->allowed;
            case 403:
                $this->label = "Denied";
                $this->reason = "403: Forbidden";
                $this->allowed = false;
                return $this->allowed;
            case 404:
                $this->label = "Denied";
                $this->reason = "404: File Not Found";
                $this->allowed = false;
                return $this->allowed;
            case 410:
                $this->label = "Denied";
                $this->reason = "410: File Permanently Deleted";
                $this->allowed = false;
                return $this->allowed;
            case 500:
            case 502:
            case 503:
                $this->label = "Ambiguous";
                $this->allowed = null;
                $this->reason = "{$this->statusCode} Error: Please retry";
                return $this->allowed;
            case 200:
            case 204:
            case 304:
                $this->label = "Allowed";
                $this->allowed = true;
                $this->reason = "{$this->statusCode}: Everything is fine";
                return $this->allowed;
            default:
                throw new \Exception("Unexpected {$this->statusCode} Status Code", $statusCode);
        }
    }

    private function processRedirect()
    {
        if (!isset($this->originalUrl)) {
            $this->setOriginalUrl($this->url);
        }

        if (count($this->redirects) > 10) {
            throw new \Exception("More than 10 Redirects, stopping");
        }

        $redirectUrl = $this->responseHeaders['Location'];
        $this->redirects[] = "{$this->statusCode} {$redirectUrl}";
        $this->setURL($redirectUrl)->setNotFetched();
        return $this->validate();
    }

    public function getRedirects($includeOriginal = false)
    {
        $redirects = $this->redirects;
        if ($includeOriginal) {
            array_unshift($redirects, "Original URL: {$this->originalUrl}");
        }

        return $redirects;
    }

    public function setRedirects($redirects)
    {
        $this->redirects = $redirects;
        return $this;
    }

    public function getOriginalUrl()
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl($url)
    {
        $this->originalUrl = $url;
        return $this;
    }
}
