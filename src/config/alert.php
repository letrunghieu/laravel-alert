<?php

return [
    /**
     * The key of the session for the messages
     */
    'session_key' => 'laravel_alert_messages',
    
    /**
     * The icons for types of message
     */
    'icons'       => [
        'success' => '<i class="fa fa-check"></i>',
        'info'    => '<i class="fa fa-info"></i>',
        'warning' => '<i class="fa fa-warning"></i>',
        'error'   => '<i class="fa fa-times"></i>',
    ],
    
    /**
     * The view name for rendering messages
     */
    'view' => 'alert::alert'
];
