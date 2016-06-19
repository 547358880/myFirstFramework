<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/19
 * Time: 11:35
 */
namespace Cake\Routing;

use Cake\Event\EventListenerInterface;
use Cake\Event\Event;

class DispatcherFilter implements  EventListenerInterface
{
    protected $config = array();

    protected $_priorty = 10;           //优先级

    protected $_defaultConfig = array(
        'when' => null,
        'for' => null
    );

    public function __construct($config = array())
    {
        if (!isset($config['priorty'])) {
            $config['priority'] = $this->_priorty;
        }
       // $this->config($config);
    }

    public function implementedEvents()
    {
        return array(
            'Dispatcher.beforeDispatch' => array(
                'callable' => 'handle'
            //    'priority' => $this->_config['priority']
            ),
            'Dispatcher.afterDispatch' => array(
                'callable' => 'handle'
             //   'priority' => $this->_config['priority']
            ),
        );
    }

    public function handle(Event $event)
    {
        $name = $event->name();
        list(, $method) = explode('.', $name);
        if (empty($this->_config['for']) && empty($this->_config['when'])) {
            return $this->{$method}($event);
        }
        if ($this->matches($event)) {
            return $this->{$method}($event);
        }
    }

    public function beforeDispatch(Event $event)
    {
    }

    public function afterDispatch(Event $event)
    {
    }
}