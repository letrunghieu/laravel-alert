<?php

namespace HieuLe\Alert;

use \Illuminate\Support\MessageBag;
use \Illuminate\Session\Store;
use \Illuminate\Config\Repository;
use \Illuminate\View\Factory;

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
     * @var type 
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

    /**
     * Supported message levels
     *
     * @var array
     */
    protected $levels = [
        'success',
        'info',
        'warning',
        'danger'
    ];

    public function __construct(Store $session, Repository $config, Factory $view, array $messages = array())
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

    public function flash()
    {
        $this->session->flash($this->_getSessionKey(), $this->messages);
        return $this;
    }

    public function dump()
    {
        $all = array();
        foreach ($this->messages as $key => $messages)
        {
            $icons   = $this->_getIcons();
            $content = $this->view->make($this->config->get('alert::view'), [
                        'icon'     => isset($icons[$key]) ? $icons[$key] : null,
                        'type'     => $key,
                        'messages' => $messages,
                    ])->render();
            $all[]   = $content;
        }
        return $all;
    }

    public function success($message)
    {
        $this->add('success', $message);
        return $this;
    }

    public function info($message)
    {
        $this->add('info', $message);
        return $this;
    }

    public function warning($message)
    {
        $this->add('warning', $message);
        return $this;
    }

    public function error($message)
    {
        $this->add('error', $message);
        return $this;
    }

    private function _getSessionKey()
    {
        return $this->config->get('alert::session_key');
    }

    private function _getIcons()
    {
        return $this->config->get('alert::icons');
    }

}
