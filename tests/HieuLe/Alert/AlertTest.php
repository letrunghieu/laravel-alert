<?php

namespace HieuLe\Alert;

/**
 * Description of AlertTest
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
class AlertTest extends \PHPUnit_Framework_TestCase
{

    public function testInit()
    {
        $alert    = $this->getAlert();
        $messages = $alert->getMessages();
        $this->assertArrayHasKey('success', $messages);
        $this->assertCount(2, $messages['success']);
    }

    public function testSuccess()
    {
        $alert    = $this->getAlert();
        $alert->success('A success message');
        $messages = $alert->getMessages();
        $this->assertArrayHasKey('success', $messages);
        $this->assertCount(3, $messages['success']);
        $this->assertContains('A success message', $messages['success']);
    }

    public function testInfo()
    {
        $alert    = $this->getAlert();
        $alert->info('An info message');
        $alert->info('Another info message');
        $messages = $alert->getMessages();
        $this->assertArrayHasKey('success', $messages);
        $this->assertArrayHasKey('info', $messages);
        $this->assertCount(2, $messages['success']);
        $this->assertCount(2, $messages['info']);
        $this->assertContains('An info message', $messages['info']);
    }
    
    public function testWarning()
    {
        $alert    = $this->getAlert();
        $alert->warning('A warning message');
        $messages = $alert->getMessages();
        $this->assertArrayHasKey('success', $messages);
        $this->assertArrayHasKey('warning', $messages);
        $this->assertCount(2, $messages['success']);
        $this->assertCount(1, $messages['warning']);
        $this->assertContains('A warning message', $messages['warning']);
    }
    
    public function testError()
    {
        $alert    = $this->getAlert();
        $alert->error('An error message');
        $messages = $alert->getMessages();
        $this->assertArrayHasKey('success', $messages);
        $this->assertArrayHasKey('error', $messages);
        $this->assertCount(2, $messages['success']);
        $this->assertCount(1, $messages['error']);
        $this->assertContains('An error message', $messages['error']);
    }
    
    protected function getAlert()
    {
        $session = $this->getMockBuilder('\Illuminate\Session\Store')
                ->setMethods(null)
                ->disableOriginalConstructor()
                ->getMock();
        $config  = $this->getMockBuilder('\Illuminate\Config\Repository')
                ->setMethods(['get'])
                ->disableOriginalConstructor()
                ->getMock();
        $view    = $this->getMockBuilder('\Illuminate\View\Factory')
                ->disableOriginalConstructor()
                ->getMock();

        $session->set('laravel_alert_messages', [
            'success' => [
                'Message success #1',
                'Message success #2',
            ],
        ]);

        $config->expects($this->any())
                ->method('get')
                ->with('alert.session_key')
                ->willReturn('laravel_alert_messages');

        $alert = new Alert($session, $config, $view);

        return $alert;
    }

}
