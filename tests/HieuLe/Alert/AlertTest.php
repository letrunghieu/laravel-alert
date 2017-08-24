<?php

namespace HieuLe\Alert;

use Orchestra\Testbench\TestCase;

/**
 * Description of AlertTest
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
class AlertTest extends TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $_session;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $_config;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $_view;

    public function setUp()
    {
        parent::setUp();

        $this->_session = $this->getMockBuilder('\Illuminate\Session\Store')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_config  = $this->getMockBuilder('\Illuminate\Config\Repository')
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->_view    = $this->getMockBuilder('\Illuminate\View\Factory')
            ->setMethods(['make'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->_config->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnMap([
                ['alert.session_key', null, 'laravel_alert_messages'],
                [
                    'alert.icons',
                    null,
                    [
                        'success' => '<i class="fa fa-check"></i>',
                        'info'    => '<i class="fa fa-info"></i>',
                        'warning' => '<i class="fa fa-warning"></i>',
                        'error'   => '<i class="fa fa-times"></i>',
                    ],
                ],
                ['alert.view', null, 'alert::alert'],
            ]);
    }

    public function testInit()
    {
        $this->assertInstanceOf(Alert::class, app('alert'));
        $alert    = new Alert($this->_session, $this->_config, $this->_view);
        $messages = $alert->getMessages();
        $this->assertCount(0, $messages);
    }

    public function testInitWithFlashedValues()
    {
        $this->_session->put('laravel_alert_messages', [
            'success' => [
                'Message success #1',
                'Message success #2',
            ],
        ]);
        $alert    = new Alert($this->_session, $this->_config, $this->_view);
        $messages = $alert->getMessages();
        $this->assertCount(1, $messages);
        $this->assertArrayHasKey('success', $messages);
        $this->assertCount(2, $messages['success']);
    }

    public function testSuccess()
    {
        $alert = new Alert($this->_session, $this->_config, $this->_view);
        $alert->success('A success message');
        $messages = $alert->getMessages();
        $this->assertArrayHasKey('success', $messages);
        $this->assertCount(1, $messages['success']);
        $this->assertContains('A success message', $messages['success']);
    }

    public function testInfo()
    {
        $alert = new Alert($this->_session, $this->_config, $this->_view);
        $alert->info('An info message');
        $alert->info('Another info message');
        $messages = $alert->getMessages();
        $this->assertArrayHasKey('info', $messages);
        $this->assertCount(2, $messages['info']);
        $this->assertContains('An info message', $messages['info']);
    }

    public function testWarning()
    {
        $alert = new Alert($this->_session, $this->_config, $this->_view);
        $alert->warning('A warning message');
        $messages = $alert->getMessages();
        $this->assertArrayHasKey('warning', $messages);
        $this->assertCount(1, $messages['warning']);
        $this->assertContains('A warning message', $messages['warning']);
    }

    public function testError()
    {
        $alert = new Alert($this->_session, $this->_config, $this->_view);
        $alert->error('An error message');
        $messages = $alert->getMessages();
        $this->assertArrayHasKey('error', $messages);
        $this->assertCount(1, $messages['error']);
        $this->assertContains('An error message', $messages['error']);
    }

    public function testDump()
    {
        $view = $this->getMockBuilder('\Illuminate\View\View')
            ->setMethods(['render'])
            ->disableOriginalConstructor()
            ->getMock();
        $view->expects($this->any())
            ->method('render')
            ->willReturn('');
        $this->_view->expects($this->at(0))
            ->method('make')
            ->with('alert::alert', [
                'icon'     => '<i class="fa fa-check"></i>',
                'type'     => 'success',
                'messages' => ['Success message'],
            ])
            ->willReturn($view);
        $this->_view->expects($this->at(1))
            ->method('make')
            ->with('alert::alert', [
                'icon'     => '<i class="fa fa-info"></i>',
                'type'     => 'info',
                'messages' => ['Info message'],
            ])
            ->willReturn($view);
        $this->_view->expects($this->at(2))
            ->method('make')
            ->with('alert::alert', [
                'icon'     => '<i class="fa fa-warning"></i>',
                'type'     => 'warning',
                'messages' => ['Warning message'],
            ])
            ->willReturn($view);
        $this->_view->expects($this->at(3))
            ->method('make')
            ->with('alert::alert', [
                'icon'     => '<i class="fa fa-times"></i>',
                'type'     => 'error',
                'messages' => ['Error message'],
            ])
            ->willReturn($view);
        $alert = new Alert($this->_session, $this->_config, $this->_view);
        $alert->success('Success message')
            ->info('Info message')
            ->warning('Warning message')
            ->error('Error message')
            ->dump();
    }

    public function testDumpWithValues()
    {
        $view = $this->getMockBuilder('\Illuminate\View\View')
            ->setMethods(['render'])
            ->disableOriginalConstructor()
            ->getMock();
        $view->expects($this->any())
            ->method('render')
            ->willReturn('');
        $this->_view->expects($this->exactly(1))
            ->method('make')
            ->with('alert::alert', [
                'icon'     => '<i class="fa fa-times"></i>',
                'type'     => 'error',
                'messages' => [
                    'field: field error #1.',
                    'field: field error #2.',
                ],
            ])
            ->willReturn($view);
        $alert  = new Alert($this->_session, $this->_config, $this->_view);
        $errors = new \Illuminate\Support\MessageBag();
        $errors->add('field', 'field error #1');
        $errors->add('field', 'field error #2');
        $alert->dump($errors->all(":key: :message."));
    }

    protected function getPackageProviders($app)
    {
        return [
            AlertServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Alert' => \HieuLe\Alert\Facades\Alert::class,
        ];
    }

}
