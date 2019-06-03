<?php
namespace vipnytt\XRobotsTagParser\Directives;

interface DirectiveInterface
{
    /**
     * Constructor
     *
     * @param string $directive
     * @param string $rule
     */
    public function __construct($directive, $rule);

    /**
     * Get directive name
     *
     * @return string
     */
    public function getDirective();

    /**
     * Get value
     *
     * @return bool|string|null
     */
    public function getValue();
}
