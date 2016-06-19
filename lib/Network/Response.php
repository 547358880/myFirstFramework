<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/18
 * Time: 13:51
 */
namespace Cake\Network;

use Cake\Core\Configure;

class Response
{
    /*
     * Buffer list of hearders
     */
    protected $_headers = array();

    /*
     * Buffer string or callable for response message
     */
    protected $_body = null;

    protected $mineTypes = array(
        'html' => array('text/html', '*/*'),
        'json' => 'application/json',
        'xml' => array('applocation/xml', 'text/xml'),
        'htm' => array('text/html', '*/*'),
        'txt' => 'text/plain',
        'text' => 'text/plain',
        'gif' => 'image/gif',
        'jpg' => 'image/jpeg'
    );

    public function __construct($options = array())
    {
        if (isset($options['body'])) {
            $this->body($options['body']);
        }

        if (!isset($options['charset'])) {
            $options['charset'] = Configure::read('App.encoding');
        }
    }

    /*
     *  Buffers a header string to be sent
     * ###Single header
     * ```
     *  header('Location', 'http://example.com');
     * ```
     *
     * ###Multiple headers
     *  ```
     *  header(array('Location' => 'http://wxample.com', 'X-Extra' => 'My header'))
     * ```
     *
     * ###String header
     * ```
     *   header('WWW-Authenticate: Negotiate')
     * ```
     *
     * @param string|array|null $header
     */
    public function header($header = null, $value)
    {
        if ($header === null) {
            return $this->_headers;
        }
        $headers = is_array($header) ? $header : array($header => $value);
        foreach ($headers as $header => $value) {
            if (is_numeric($header)) {
                list($header, $value) = array($value, null);
            }
            if ($value === null) {
                list($header, $value) = explode(':', $header, 2);
            }
            $this->_headers[$header] = is_array($value) ? array_map('trim', $value) : trim($value);
        }
        return $this->_headers;
    }

    /*
     * Buffers the message to be sent
     */
    public function body($content = null)
    {
        if ($content === null) {
            return $this->_body;
        }
        return $this->_body = $content;
    }
}

