<?php

namespace HieuLe\Alert;

use \Illuminate\Support\MessageBag;
use \Illuminate\Contracts\Session\Session;
use \Illuminate\Contracts\Config\Repository;
use \Illuminate\Contracts\View\Factory;

/**
 * The global site message system for Laravel
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
class Alert extends MessageBag
{

    /**
     * Laravel session
     *
     * @var Session
     */
    protected $session;

    /**
     * Laravel config repository
     *
     * @var Repository
     */
    protected $config;

    /**
     * Laravel view factory
     *
     * @var Factory
     */
    protected $view;

    public function __construct(Session $session, Repository $config, Factory $view, array $messages = array())
    {
        $this->session = $session;
        $this->config  = $config;
        $this->view    = $view;
        if ($session->has($this->_getSessionKey()))
        {
            $messages = array_merge_recursive(
                    $session->get($this->_getSessionKey()), $messages
            );
        }

        parent::__construct($messages);
    }

    /**
     * Flash current message to the next request
     * 
     * @return \HieuLe\Alert\Alert
     */
    public function flash()
    {
        $this->session->flash($this->_getSessionKey(), $this->messages);
        return $this;
    }

    /**
     * Write current messages into string
     * 
     * @param array $errors laravel validation formated errors to be merged
     * 
     * @return string
     */
    public function dump(array $errors = [])
    {
        $all            = array();
        $mergedMessages = array_merge_recursive($this->messages, ['error' => $errors]);
        foreach ($mergedMessages as $key => $messages)
        {
            $icons = $this->config->get('alert.icons');
            if (empty($messages))
            {
                continue;
            }
            $content = $this->view->make($this->config->get('alert.view'), [
                        'icon'     => isset($icons[$key]) ? $icons[$key] : null,
                        'type'     => $key,
                        'messages' => $messages,
                    ])->render();
            $all[]   = $content;
        }
        return empty($all) ? "" : implode("\n", $all);
    }

    /**
     * Add a success message
     * 
     * @param string $message
     * @return \HieuLe\Alert\Alert
     */
    public function success($message)
    {
        $this->add('success', $message);
        return $this;
    }

    /**
     * Add an info message
     * 
     * @param string $message
     * @return \HieuLe\Alert\Alert
     */
    public function info($message)
    {
        $this->add('info', $message);
        return $this;
    }

    /**
     * Add a warning message
     * 
     * @param string $message
     * @return \HieuLe\Alert\Alert
     */
    public function warning($message)
    {
        $this->add('warning', $message);
        return $this;
    }

    /**
     * Add an error message
     * 
     * @param string $message
     * @return \HieuLe\Alert\Alert
     */
    public function error($message)
    {
        $this->add('error', $message);
        return $this;
    }

    /**
     * Get the session key name for messages
     * 
     * @return string
     */
    private function _getSessionKey()
    {
        return $this->config->get('alert.session_key');
    }

}
