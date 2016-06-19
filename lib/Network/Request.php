<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/18
 * Time: 13:51
 */
namespace Cake\Network;

use ArrayAccess;
use Cake\Core\Configure;

class Request implements ArrayAccess
{
    /*
     * Array of parameters parsed from the URL
     */
    public $params = array(
        'plugin' => null,
        'controller' => null,
        'action' => null,
        '_ext' => null,
        'pass' => array()
    );

    /*
     * Array of querystring arguments
     */
    public $query = array();

    /*
     *  The Url string user for the request
     */
    public $url;

    /*
     * Array of POST data
     */
    public $data = array();

    /*
     * Base URL path
     */
    public $base;

    public static function createFromGlobals()
    {
        list($base, $webroot) = static::_base();
        $config = array(
            'query' => $_GET,
            'post' => $_POST,
            'files' => $_FILES,
            'cookies' => $_COOKIE,
            'base' => ''
        );
        $config['url'] = static::_url($config);
        return new static($config);
    }

    /**
     * Get the request uri. Looks in PATH_INFO first, as this is the exact value we need prepared
     * by PHP. Following that, REQUEST_URI, PHP_SELF, HTTP_X_REWRITE_URL and argv are checked in that order.
     * Each of these server variables have the base path, and query strings stripped off
     *
     * @param array $config Configuration to set.
     * @return string URI The CakePHP request path that is being accessed.
     */
    protected static function _url($config)
    {
        if (!empty($_SERVER['PATH_INFO'])) {
            return $_SERVER['PATH_INFO'];
        }
        if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '://') === false) {
            $uri = $_SERVER['REQUEST_URI'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
            $fullBaseUrl = Configure::read('App.fullBaseUrl');
            if (strpos($uri, $fullBaseUrl) === 0) {
                $uri = substr($_SERVER['REQUEST_URI'], strlen($fullBaseUrl));
            }
        } elseif (isset($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_NAME'])) {
            $uri = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']);
        } elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
            $uri = $_SERVER['HTTP_X_REWRITE_URL'];
        } elseif ($var = env('argv')) {
            $uri = $var[0];
        }

        $base = $config['base'];

        if (strlen($base) > 0 && strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }
        if (strpos($uri, '?') !== false) {
            list($uri) = explode('?', $uri, 2);
        }
        if (empty($uri) || $uri === '/' || $uri === '//' || $uri === '/index.php') {
            $uri = '/';
        }
        $endsWithIndex = '/webroot/index.php';
        $endsWithLength = strlen($endsWithIndex);
        if (strlen($uri) >= $endsWithLength &&
            substr($uri, -$endsWithLength) === $endsWithIndex
        ) {
            $uri = '/';
        }
        return $uri;
    }

    /*
     *
     */
    public function __construct($config = array())
    {
        if (is_string($config)) {
            $config = array('url' => $config);
        }
        $config += array(
            'params' => $this->params,
            'query' => [],
            'post' => [],
            'files' => [],
            'cookies' => [],
            'environment' => [],
            'url' => '',
            'base' => '',
            'webroot' => '',
            'input' => null,
        );

        $this->_setConfig($config);
    }

    /*
     * Process the config/setting data into properties
     */
    protected function _setConfig($config)
    {
        $this->url = $config['url'];
        $config['post'] = $this->_processPost($config['post']);
        $this->query = $this->_processGet($config['query']);
        //pr($this->query);
    }

    protected function _processPost($data)
    {

    }

    /*
     * Process the GET parameters and move things into the object.
     */
    protected function _processGet($query)
    {
        $unsetUrl = '/' . str_replace(array('.', ' '), '_', urldecode($this->url));
        unset($query[$unsetUrl]);
        if (strpos($this->url, '?') !== false) {
            list(, $querystr) = explode('?', $this->url);
            parse_str($querystr, $queryArgs);
            $query += $queryArgs;
        }
        return $query;
    }

    /*
     * Return a base URL and ses the proper webroot
     */
    protected static function _base()
    {
        $base = $webroot = $baseUrl = null;
        $config = Configure::read('App');
        extract($config);

        if ($base !== false && $base !== null) {
            return [$base, $base . '/'];
        }

        if (!$baseUrl) {
            $base = dirname(env('PHP_SELF'));
            // Clean up additional / which cause following code to fail..
            $base = preg_replace('#/+#', '/', $base);

            $indexPos = strpos($base, '/' . $webroot . '/index.php');
            if ($indexPos !== false) {
                $base = substr($base, 0, $indexPos) . '/' . $webroot;
            }
            if ($webroot === basename($base)) {
                $base = dirname($base);
            }

            if ($base === DIRECTORY_SEPARATOR || $base === '.') {
                $base = '';
            }
            $base = implode('/', array_map('rawurlencode', explode('/', $base)));
            return [$base, $base . '/'];
        }

        $file = '/' . basename($baseUrl);
        $base = dirname($baseUrl);

        if ($base === DIRECTORY_SEPARATOR || $base === '.') {
            $base = '';
        }
        $webrootDir = $base . '/';

        $docRoot = env('DOCUMENT_ROOT');
        $docRootContainsWebroot = strpos($docRoot, $webroot);

        if (!empty($base) || !$docRootContainsWebroot) {
            if (strpos($webrootDir, '/' . $webroot . '/') === false) {
                $webrootDir .= $webroot . '/';
            }
        }
        return [$base . $file, $webrootDir];
    }

    /*
     *
     */
    public function offsetSet($name, $value)
    {
        $this->params[$name] = $value;
    }

    public function offsetGet($name)
    {
        if (isset($this->params[$name])) {
            return $this->prams[$name];
        }
        if ($name === 'url') {
            return $this->query;
        }
        if ($name === 'data') {
            return $this->data;
        }
        return null;
    }

    public function offsetExists($name)
    {
        if ($name === 'url' || $name === 'data') {
            return true;
        }
        return isset($this->params[$name]);
    }

    public function offsetUnset($name)
    {
        unset($this->params[$name]);
    }
}
