<?php
namespace vipnytt\XRobotsTagParser\Directives;

/**
 * Class Generic
 *
 * @package vipnytt\XRobotsTagParser\Directives
 */
final class Generic implements DirectiveInterface
{
    /**
     * Directive
     * @param string
     */
    protected $directive;

    /**
     * Rule string
     * @param string
     */
    protected $rule;

    /**
     * Constructor
     *
     * @param string $directive
     * @param string $rule
     */
    public function __construct($directive, $rule)
    {
        $this->directive = mb_strtolower($directive);
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
     * @return true
     */
    public function getValue()
    {
        return true;
    }
}
