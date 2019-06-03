<?php
namespace vipnytt;

use vipnytt\XRobotsTagParser\Directives;
use vipnytt\XRobotsTagParser\Exceptions\XRobotsTagParserException;
use vipnytt\XRobotsTagParser\RobotsTagInterface;

/**
 * Class XRobotsTagParser
 * X-Robots-Tag HTTP header parser
 *
 * @package vipnytt
 *
 * @author VIP nytt (vipnytt@gmail.com)
 * @author Jan-Petter Gundersen (europe.jpg@gmail.com)
 *
 * Project:
 * @link https://github.com/VIPnytt/RobotsTagParser
 * @license https://opensource.org/licenses/MIT MIT license
 *
 * Specification:
 * @link https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag#using-the-x-robots-tag-http-header
 */
class XRobotsTagParser implements RobotsTagInterface
{
    /**
     * User-Agent string
     *
     * @var string
     */
    protected $userAgent = self::USER_AGENT;

    /**
     * User-Agent for rule selection
     *
     * @var string
     */
    protected $userAgentMatch = self::USER_AGENT;

    /**
     * Current rule
     *
     * @var string
     */
    protected $currentRule = '';

    /**
     * User-Agent for the current rule
     *
     * @var string
     */
    protected $currentUserAgent;

    /**
     * Rule array
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Constructor
     *
     * @param string $userAgent
     * @param array|null $headers
     */
    public function __construct($userAgent = self::USER_AGENT, $headers = null)
    {
        $this->userAgent = $userAgent;
        if (!empty($headers)) {
            $this->parse($headers);
        }
    }

    /**
     * Parse HTTP headers
     *
     * @param array $headers
     * @return void
     */
    public function parse(array $headers)
    {
        foreach ($headers as $header) {
            $parts = array_map('trim', mb_split(':', mb_strtolower($header), 2));
            if (count($parts) < 2 || $parts[0] != mb_strtolower(self::HEADER_RULE_IDENTIFIER)) {
                // Header is not a rule
                continue;
            }
            $this->currentRule = $parts[1];
            $this->detectDirectives();
        }
        $this->matchUserAgent();
    }

    /**
     * Detect directives in rule
     *
     * @return void
     */
    protected function detectDirectives()
    {
        $directives = array_map('trim', mb_split(',', $this->currentRule));
        $pair = array_map('trim', mb_split(':', $directives[0], 2));
        if (count($pair) == 2 && !in_array($pair[0], self::DIRECTIVES)) {
            $this->currentUserAgent = $pair[0];
            $directives[0] = $pair[1];
        }
        foreach ($directives as $rule) {
            $directive = trim(mb_split(':', $rule, 2)[0]);
            if (in_array($directive, self::DIRECTIVES)) {
                $this->addRule($directive);
            }
        }
        $this->cleanup();
    }

    /**
     * Add rule
     *
     * @param string $directive
     * @return void
     * @throws XRobotsTagParserException
     */
    protected function addRule($directive)
    {
        if (!isset($this->rules[$this->currentUserAgent])) {
            $this->rules[$this->currentUserAgent] = [];
        }
        switch ($directive) {
            case self::DIRECTIVE_UNAVAILABLE_AFTER:
                $object = new Directives\UnavailableAfter($directive, $this->currentRule);
                break;
            default:
                $object = new Directives\Generic($directive, $this->currentRule);
        }
        $this->rules[$this->currentUserAgent] = array_merge($this->rules[$this->currentUserAgent], [$object->getDirective() => $object->getValue()]);
    }

    /**
     * Cleanup before next rule is read
     *
     * @return void
     */
    protected function cleanup()
    {
        $this->currentRule = '';
        $this->currentUserAgent = self::USER_AGENT;
    }

    /**
     * Find the most rule-matching User-Agent
     *
     * @return string
     */
    protected function matchUserAgent()
    {
        $userAgentParser = new UserAgentParser($this->userAgent);
        $this->userAgentMatch = (($match = $userAgentParser->getMostSpecific(array_keys($this->rules))) !== false) ? $match : self::USER_AGENT;
        return $this->userAgentMatch;
    }

    /**
     * Return all applicable rules
     *
     * @return array
     */
    public function getRules()
    {
        $rules = [];
        // Default UserAgent
        if (isset($this->rules[self::USER_AGENT])) {
            $rules = array_merge($rules, $this->rules[self::USER_AGENT]);
        }
        // Matching UserAgent
        if (isset($this->rules[$this->userAgentMatch])) {
            $rules = array_merge($rules, $this->rules[$this->userAgentMatch]);
        }
        // Result
        return $rules;
    }

    /**
     * Export all rules for all UserAgents
     *
     * @return array
     */
    public function export()
    {
        return $this->rules;
    }
}
