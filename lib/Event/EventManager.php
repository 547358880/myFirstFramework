<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/19
 * Time: 10:37
 */
namespace Cake\Event;

class EventManager
{
    public static $defaultPriority = 10;

    /*
     * Internal flag to distinguish a common manager from the singleton (manager是不是个单列)
     */
    protected $_isGolbal = false;

    /**
     * The globally available instance, used for dispatching events attached from any scope
     *
     * @var \Cake\Event\EventManager
     */
    protected static $_generalManager = null;

    /*
     * List of listerer callbacks
     */
    protected $_listeners = array();

    public static function instance($manager = null)
    {
        if ($manager instanceof EventManager) {
            static::$_generalManager = $manager;
        }
        if (empty(static::$_generalManager)) {
            static::$_generalManager = new EventManager();
        }

        static::$_generalManager->_isGlobal = true;
        return static::$_generalManager;
    }

    /*
     * Adds a new listener to an event
     * Binding an EventListerInterface
     */
    public function on($eventKey = null, $options = array(), $callable = null)
    {
        if ($eventKey instanceof EventListenerInterface)
        {
            $this->_attachSubscriber($eventKey);
            return;
        }
        $argCount = func_num_args();
        if ($argCount === 2) {
            $this->_listeners[$eventKey][static::$defaultPriority][] = [
                'callable' => $options
            ];
            return;
        }
        if ($argCount === 3) {
            $priority = isset($options['priority']) ? $options['priority'] : static::$defaultPriority;
            $this->_listeners[$eventKey][$priority][] = [
                'callable' => $callable
            ];
            return;
        }
        throw new InvalidArgumentException('Invalid arguments for EventManager::on().');
    }

    protected function _attachSubscriber(EventListenerInterface $subscriber)
    {
        foreach ((array)$subscriber->implementedEvents() as $eventKey => $function) {
            $options = array();
            $method = $function;
            if (is_array($function) && isset($function['callable'])) {
                list($method, $options) = $this->_extractCallable($function, $subscriber);
            } elseif (is_array($function) && is_numeric(key($function))) {

            }
            if (is_string($method)) {
                $method = array($subscriber, $function);
            }
            $this->on($eventKey, $options, $method);
        }
    }

    protected function _extractCallable($function, $object)
    {
        $method = $function['callable'];
        $options = $function;
        unset($options['callable']);
        if (is_string($method)) {
            $method = array($object, $method);
        }
        return array($method, $options);
    }

    /*
     * Dispatches a new event to all configured listeners
     */
    public function dispatch($event)
    {
        if (is_string($event)) {
            $event = new Event($event);
        }
        $listeners = $this->listerers($event->name());
        if (empty($listeners)) {
           return $event;
        }

        foreach ($listeners as $listener) {
            if ($event->isStopped()) {
                break;
            }
           // pr($listener['callable']);
           $result = $this->_callListener($listener['callable'], $event);
            //$result = call_user_func($listener['callable'], $event);

            //$result = call_user_func_array($listener, $event->data());
            if ($result === false) {
                $event->stopPropagation();
            }
            if ($result !== null) {
                $event->result = $result;
            }
        }
        return $event;
    }

    protected function _callListener($listener, Event $event) {
        $data = $event->data();
        $length = count($data);
        if ($length) {
            $data = array_values($data);
        }
        switch ($length) {
            case 0:
                return $listener($event);
            case 1:
                return $listener($event, $data[0]);
            case 2:
                return $listener($event, $data[0], $data[1]);
            case 3:
                return $listener($event, $data[0], $data[1], $data[2]);
            default:
                array_unshift($data, $event);
                return call_user_func_array($listener, $data);
        }
    }

    /*
     * Returns a list of all listeners for an eventKey in the order they should be called
     */
    public function listerers($eventKey)
    {
        $localListeners = array();
        if (!$this->_isGolbal) {
            $localListeners = $this->prioritisedListerers($eventKey);
            $localListeners = empty($localListeners) ? [] : $localListeners;
        }
        $globalListeners = static::instance()->prioritisedListerers($eventKey);
        $globalListeners = empty($globalListeners) ? [] : $globalListeners;
        $priorities = array_merge(array_keys($globalListeners), array_keys($localListeners));
        $priorities = array_unique($priorities);
        asort($priorities);

        $result = [];
        foreach ($priorities as $priority) {
            if (isset($globalListeners[$priority])) {
                $result = array_merge($result, $globalListeners[$priority]);
            }
            if (isset($localListeners[$priority])) {
                $result = array_merge($result, $localListeners[$priority]);
            }
        }
        return $result;
    }

    public function prioritisedListerers($eventKey)
    {
        if (empty($this->_listeners[$eventKey])) {
            return array();
        }
        return $this->_listeners[$eventKey];
    }
}