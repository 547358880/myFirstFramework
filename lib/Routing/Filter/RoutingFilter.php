<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/19
 * Time: 11:32
 */
namespace Cake\Routing\Filter;

use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;

class RoutingFilter extends DispatcherFilter
{

    public function beforeDispatch(Event $event)
    {
        $request = $event->data['request'];
        Router::setRequestInfo($request);
    }
}