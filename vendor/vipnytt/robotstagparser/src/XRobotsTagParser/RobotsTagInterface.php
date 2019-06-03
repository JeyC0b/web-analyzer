<?php
namespace vipnytt\XRobotsTagParser;

/**
 * Interface RobotsTagInterface
 *
 * @package vipnytt\XRobotsTagParser
 */
interface RobotsTagInterface
{
    /**
     * HTTP header prefix
     */
    const HEADER_RULE_IDENTIFIER = 'X-Robots-Tag';

    /**
     * Default User-Agent
     */
    const USER_AGENT = '';

    /**
     * Directives
     * @link https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag#valid-indexing--serving-directives
     */
    const DIRECTIVE_ALL = 'all';
    const DIRECTIVE_NONE = 'none';
    const DIRECTIVE_NO_ARCHIVE = 'noarchive';
    const DIRECTIVE_NO_FOLLOW = 'nofollow';
    const DIRECTIVE_NO_IMAGE_INDEX = 'noimageindex';
    const DIRECTIVE_NO_INDEX = 'noindex';
    const DIRECTIVE_NO_ODP = 'noodp';
    const DIRECTIVE_NO_SNIPPET = 'nosnippet';
    const DIRECTIVE_NO_TRANSLATE = 'notranslate';
    const DIRECTIVE_UNAVAILABLE_AFTER = 'unavailable_after';

    /**
     * All directives in one array
     */
    const DIRECTIVES = [
        self::DIRECTIVE_ALL,
        self::DIRECTIVE_NONE,
        self::DIRECTIVE_NO_ARCHIVE,
        self::DIRECTIVE_NO_FOLLOW,
        self::DIRECTIVE_NO_IMAGE_INDEX,
        self::DIRECTIVE_NO_INDEX,
        self::DIRECTIVE_NO_ODP,
        self::DIRECTIVE_NO_SNIPPET,
        self::DIRECTIVE_NO_TRANSLATE,
        self::DIRECTIVE_UNAVAILABLE_AFTER,
    ];
}
