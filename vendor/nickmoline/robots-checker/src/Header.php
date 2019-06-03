<?php

namespace NickMoline\Robots;

use NickMoline\Robots\Base as RobotsBase;

class Header extends Status
{
    protected $userAgentAllowedLine = null;
    protected $globalAllowedLine = null;

    public static function createFromExisting(RobotsBase $existing, RobotsBase $robots = null)
    {
        if (!$robots) {
            $robots = new Header();
        }

        $robots = Status::createFromExisting($existing, $robots);

        return $robots;
    }

    public function validate()
    {
        if (!$this->isFetched()) {
            $validStatus = parent::validate();
            if (!$validStatus) {
                return false;
            }
        }

        $headers = $this->getResponseHeaders();
        if (!isset($headers['X-Robots-Tag'])) {
            if ($this->isAllowed()) {
                $this->setReason("No X-Robots-Tag HTTP Header");
            }
            return $this->isAllowed();
        }

        $tags = $headers['X-Robots-Tag'];

        if (!is_array($tags)) {
            $tags = explode("\n", $tags);
        }

        foreach ($tags as $line) {
            $this->processTagLine($line);
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

        return $this->isAllowed();
    }

    private function processTagLine($line)
    {
        $parts = explode(":", $line);
        if (count($parts) > 1) {
            if (!stristr($parts[0], $this->userAgent)) {
                return null;
            }

            $allowed = $this->lineAllowed($parts[1]);
            if (!is_null($allowed)) {
                $this->userAgentAllowed = $allowed;
                $this->userAgentLine = $line;
            }
            return $allowed;
        }

        $allowed = $this->lineAllowed($line);
        if (!is_null($allowed)) {
            $this->globalAllowed = $allowed;
            $this->globalAllowedLine = $line;
        }
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
}
