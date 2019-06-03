<?php

namespace NickMoline\Robots;

use NickMoline\Robots\Base as RobotsBase;
use PHPHtmlParser\Dom;

class Meta extends Status
{
    protected $userAgentAllowedLine = null;
    protected $globalAllowedLine = null;

    public static function createFromExisting(RobotsBase $existing, RobotsBase $robots = null)
    {
        if (!$robots) {
            $robots = new Meta();
        }

        $robots = Status::createFromExisting($existing, $robots);

        return $robots;
    }

    public function validate()
    {
        $dom = new Dom;
        $dom->load($this->contents);
        $metas = $dom->find('meta');

        foreach ($metas as $meta) {
            $this->processTagLine($meta);
        }

        if (!is_null($this->userAgentAllowed)) {
            $this->setAllowed($this->userAgentAllowed)
                 ->setReason($this->userAgentAllowedLine);
            return $this->userAgentAllowed;

        }

        if (!is_null($this->globalAllowed)) {
            $this->setAllowed($this->globalAllowed)
                 ->setReason($this->globalAllowedLine);

            return $this->globalAllowed;
        }

        if ($this->isAllowed()) {
            $this->setReason("No Meta Robots or Meta {$this->userAgent} Tag Present");
        }
        return $this->isAllowed();
    }

    private function lineAllowed($line)
    {
        $parts = preg_split("@[\s,]+@", strtolower($line));

        if (in_array("noindex", $parts)) {
            return false;
        }
        if (in_array("index", $parts)) {
            return true;
        }
        return null;
    }

    private function processTagLine($meta)
    {
        $name = $meta->getAttribute("name");
        if (!$name) return null;

        if (stristr($name, $this->userAgent)) {
            $content = $meta->getAttribute("content");
            $allowed = $this->lineAllowed($content);
            if (!is_null($allowed)) {
                $this->userAgentAllowed = $allowed;
                $this->userAgentLine = $meta->outerHtml;
                return $allowed;
            }
        }

        if (strtolower($name) == "robots") {
            $content = $meta->getAttribute("content");
            $allowed = $this->lineAllowed($content);
            if (!is_null($allowed)) {
                $this->globalAllowed = $allowed;
                $this->globalAllowedLine = $meta->outerHtml;
                return $allowed;
            }
        }

        return null;
    }
}
