<?php
namespace vipnytt\XRobotsTagParser\Adapters;

use Psr\Http\Message\ResponseInterface;
use vipnytt\XRobotsTagParser;

/**
 * Class GuzzleHttp
 *
 * Parse from an object witch implements the \Psr\Http\Message\ResponseInterface
 *
 * @package vipnytt\XRobotsTagParser\Adapters
 */
class GuzzleHttp extends XRobotsTagParser
{
    /**
     * Constructor
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string $userAgent
     * @throws XRobotsTagParser\Exceptions\XRobotsTagParserException
     */
    public function __construct(ResponseInterface $response, $userAgent = '')
    {
        parent::__construct($userAgent);
        $headers = [];
        foreach ($response->getHeader(parent::HEADER_RULE_IDENTIFIER) as $name => $values) {
            $headers[] = $name . ': ' . implode(' ', $values) . "\r\n";
        }
        $this->parse($headers);
    }
}
