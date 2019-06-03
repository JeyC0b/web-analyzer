<?php
namespace vipnytt\XRobotsTagParser\Directives;

/**
 * Class UnavailableAfter
 *
 * @package vipnytt\XRobotsTagParser\Directives
 */
final class UnavailableAfter implements DirectiveInterface
{
    /**
     * Google date format
     * @link https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag
     */
    const DATE_GOOGLE = 'd M Y H:i:s T';

    /**
     * Supported date formats
     */
    const SUPPORTED_DATE_FORMATS = [
        DATE_RFC850,
        self::DATE_GOOGLE,
    ];

    /**
     * Current directive
     * @var string
     */
    protected $directive;

    /**
     * Current rule string
     * @var string
     */
    private $rule;

    /**
     * Constructor
     *
     * @param string $directive
     * @param string $rule
     */
    public function __construct($directive, $rule)
    {
        $this->directive = $directive;
        $this->rule = $rule;
    }

    /**
     * Get directive name
     *
     * @return string
     */
    public function getDirective()
    {
        return $this->directive;
    }

    /**
     * Get value
     *
     * @return string|null
     */
    public function getValue()
    {
        $parts = mb_split(',', trim(mb_substr($this->rule, mb_stripos($this->rule, $this->directive) + mb_strlen($this->directive) + 1)));
        $count = count($parts);
        for ($num = 1; $num <= $count; $num++) {
            foreach (self::SUPPORTED_DATE_FORMATS as $format) {
                $dateTime = date_create_from_format($format, trim(implode(',', array_slice($parts, 0, $num))));
                if ($dateTime !== false) {
                    return date_format($dateTime, DATE_RFC850);
                }
            }
        }
        return null;
    }
}
