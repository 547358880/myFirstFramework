<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/19
 * Time: 10:32
 */
namespace Cake\Event;

class Event
{
    protected $_name = null;

    public $data = null;

    public $result = null;

    protected $_stopped = false;

    /**
     * Constructor
     *
     * ### Examples of usage:
     *
     * ```
     *  $event = new Event('Order.afterBuy', $this, ['buyer' => $userData]);
     *  $event = new Event('User.afterRegister', $UserModel);
     * ```
     *
     * @param string $name Name of the event
     * @param object|null $subject the object that this event applies to (usually the object that is generating the event)
     * @param array|null $data any value you wish to be transported with this event to it can be read by listeners
     */
    public function __construct($name, $subject = null, $data = null)
    {
        $this->_name = $name;
        $this->data = $data;
        $this->_subject = $subject;
    }

    /**
     * Dynamically returns the name and subject if accessed directly
     *
     * @param string $attribute Attribute name.
     * @return mixed
     */
    public function __get($attribute)
    {
        if ($attribute === 'name' || $attribute === 'subject') {
            return $this->{$attribute}();
        }
    }

    public function name()
    {
        return $this->_name;
    }

    public function subject()
    {
        return $this->_subject;
    }

    public function data()
    {
        return (array)$this->data;
    }

    public function isStopped()
    {
        return $this->_stopped;
    }

    /**
     * Stops the event from being used anymore
     *
     * @return void
     */
    public function stopPropagation()
    {
        $this->_stopped = true;
    }
}