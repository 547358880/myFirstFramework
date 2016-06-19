<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/19
 * Time: 14:39
 */
namespace Cake\Routing\Filter;

use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;

class AssetFilter extends DispatcherFilter
{
    protected $_cacheTime = "+1 day";

    public function __construct($config = array())
    {
        if (!empty($config['cacheTime'])) {
            $this->_cacheTime = $config['cacheTime'];
        }
        parent::__construct($config);
    }

    /*
     * 用于css,js,图片的缓存
     */
    public function beforeDispatch(Event $event)
    {
        $request = $event->data['request'];
        $url = urldecode($request->url);
        if (strpos($url, '..') !== false || strpos($url, '.') === false) {
            return null;
        }
        $assetFile = $this->_getAssetFile($url);
        if ($assetFile === null || !file_exists($assetFile)) {
            return null;
        }
        $response = $event->data['response'];
        $event->stopPropagation();
        //设置文件缓存时间
    }

    protected function _getAssetFile($url)
    {

    }
}