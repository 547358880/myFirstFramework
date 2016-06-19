<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/18
 * Time: 13:49
 */
namespace Cake\Routing;

use Cake\Event\EventListenerInterface;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Event\Event;
use Cake\Event\EventManager;

class Dispatcher
{
    protected $_eventManager = null;

    public function dispatch(Request $request, Response $response)
    {
       $beforeEvent = $this->dispatchEvent('Dispatcher.beforeDispatch', compact('request', 'response'));
    }

    public function eventManager(EventManager $eventManager = null)
    {
        if (empty($this->_eventManager)) {
            $this->_eventManager = new EventManager();
        }
        return $this->_eventManager;
    }

    /*
     *
     */
    public function dispatchEvent($name, $data= null, $subject = null)
    {
        if ($subject === null) {
            $subject = $this;
        }
        $event = new Event($name, $subject, $data);
        $this->eventManager()->dispatch($event);
        return $event;
    }

    /*
     * Add a filter to this dispatcher
     */
    public function addFilter(EventListenerInterface $filter)
    {
        $this->_filters[] = $filter;
        $this->eventManager()->on($filter);
    }
}