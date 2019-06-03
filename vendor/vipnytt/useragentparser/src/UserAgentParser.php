<?php
/**
 * vipnytt/UserAgentParser
 *
 * @link https://github.com/VIPnytt/UserAgentParser
 * @license https://github.com/VIPnytt/UserAgentParser/blob/master/LICENSE The MIT License (MIT)
 */

namespace vipnytt;

use vipnytt\UserAgentParser\Exceptions;

/**
 * Class UserAgentParser
 *
 * @link https://tools.ietf.org/html/rfc7231
 * @link https://tools.ietf.org/html/rfc7230
 *
 * @package vipnytt
 */
class UserAgentParser
{
    /**
     * RFC 7231 - Section 5.5.3 - User-agent
     */
    const RFC_README = 'https://tools.ietf.org/html/rfc7231#section-5.5.3';

    /**
     * Origin product
     * @var string
     */
    private $originProduct;

    /**
     * Product
     * @var string
     */
    private $product;

    /**
     * Version
     * @var float|int|string|null
     */
    private $version;

    /**
     * UserAgentParser constructor
     *
     * @param string $product
     * @param float|int|string|null $version
     */
    public function __construct($product, $version = null)
    {
        $this->product = $product;
        $this->version = $version;
        if (strpos($this->product, '/') !== false) {
            $this->split();
        }
        $this->validateProduct();
        $this->validateVersion();
    }

    /**
     * Split Product and Version
     *
     * @return bool
     */
    private function split()
    {
        if (count($parts = explode('/', trim($this->product . '/' . $this->version, '/'), 2)) === 2) {
            $this->product = $parts[0];
            $this->version = $parts[1];
        }
        return true;
    }

    /**
     * Validate the Product format
     * @link https://tools.ietf.org/html/rfc7231#section-5.5.3
     * @link https://tools.ietf.org/html/rfc7230#section-3.2.4
     *
     * @return bool
     * @throws Exceptions\ProductException
     */
    private function validateProduct()
    {
        $this->blacklistCheck($this->product);
        $this->originProduct = $this->product;
        if ($this->originProduct !== ($this->product = $this->strip($this->product))) {
            trigger_error("Product name contains invalid characters. Truncated to `$this->product`.", E_USER_NOTICE);
        }
        if (empty($this->product)) {
            throw new Exceptions\ProductException('Product string cannot be empty.');
        }
        return true;
    }

    /**
     * Check for blacklisted strings or characters
     *
     * @param float|int|string|null $input
     * @return bool
     * @throws Exceptions\FormatException
     */
    private function blacklistCheck($input)
    {
        foreach ([
                     'mozilla',
                     'compatible',
                     '(',
                     ')',
                 ] as $string) {
            if (stripos($input, $string) !== false) {
                throw new Exceptions\FormatException('Invalid User-agent format (`' . trim($this->product . '/' . $this->version, '/') . '`). Examples of valid User-agents: `MyCustomBot`, `MyFetcher-news`, `MyCrawler/2.1` and `MyBot-images/1.2`. See also ' . self::RFC_README);
            }
        }
        return true;
    }

    /**
     * Strip invalid characters
     *
     * @param string|string[] $string
     * @return string|string[]|null
     */
    private function strip($string)
    {
        return preg_replace('/[^\x21-\x7E]/', '', $string);
    }

    /**
     * Validate the Version and it's format
     * @link https://tools.ietf.org/html/rfc7231#section-5.5.3
     *
     * @return bool
     * @throws Exceptions\VersionException
     */
    private function validateVersion()
    {
        $this->blacklistCheck($this->version);
        if (!empty($this->version) &&
            (
                str_replace('+', '', filter_var($this->version, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) !== $this->version ||
                version_compare($this->version, '0.0.1', '>=') === false
            )
        ) {
            throw new Exceptions\VersionException("Invalid version format (`$this->version`). See http://semver.org/ for guidelines. In addition, dev/alpha/beta/rc tags is disallowed. See also " . self::RFC_README);
        }
        $this->version = trim($this->version, '.0');
        return true;
    }

    /**
     * Get User-agent
     *
     * @return string
     */
    public function getUserAgent()
    {
        $product = $this->getProduct();
        $version = $this->getVersion();
        return $version === null ? $product : $product . '/' . $version;
    }

    /**
     * Get product
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Get version
     *
     * @return float|int|string|null
     */
    public function getVersion()
    {
        return empty($this->version) ? null : $this->version;
    }

    /**
     * Find the best matching User-agent
     *
     * @param string[] $userAgents
     * @return string|false
     */
    public function getMostSpecific(array $userAgents)
    {
        $array = [];
        foreach ($userAgents as $string) {
            // Strip non-US-ASCII characters
            $array[$string] = strtolower($this->strip($string));
        }
        foreach (array_map('strtolower', $this->getUserAgents()) as $generated) {
            if (($result = array_search($generated, $array)) !== false) {
                // Match found
                return $result;
            }
        }
        return false;
    }

    /**
     * Get an array of all possible User-agent combinations
     *
     * @return string[]
     */
    public function getUserAgents()
    {
        return array_merge(
            preg_filter('/^/', $this->product . '/', $this->getVersions()),
            $this->getProducts()
        );
    }

    /**
     * Get versions
     *
     * @return float[]|int[]|string[]
     */
    public function getVersions()
    {
        while (
            !empty($this->version) &&
            substr_count($this->version, '.') < 2
        ) {
            $this->version .= '.0';
        }
        // Remove part by part of the version.
        $result = array_merge(
            [$this->version],
            $this->explode($this->version, '.')
        );
        asort($result);
        usort($result, function ($a, $b) {
            // PHP 7: Switch to the <=> "Spaceship" operator
            return strlen($b) - strlen($a);
        });
        return $this->filterDuplicates($result);
    }

    /**
     * Explode
     *
     * @param string $string
     * @param string $delimiter
     * @return string[]
     */
    private function explode($string, $delimiter)
    {
        $result = [];
        while (($pos = strrpos($string, $delimiter)) !== false) {
            $result[] = ($string = substr($string, 0, $pos));
        }
        return $result;
    }

    /**
     * Filter duplicates from an array
     *
     * @param string[] $array
     * @return string[]
     */
    private function filterDuplicates($array)
    {
        $result = [];
        foreach ($array as $value) {
            if (!in_array($value, $result)) {
                $result[] = $value;
            }
        }
        return array_filter($result);
    }

    /**
     * Get products
     *
     * @return string[]
     */
    public function getProducts()
    {
        return $this->filterDuplicates(array_merge([
            $this->originProduct,
            $this->product,
            preg_replace('/[^A-Za-z0-9]/', '', $this->product), // in case of special characters
        ],
            $this->explode($this->product, '-')
        ));
    }
}
