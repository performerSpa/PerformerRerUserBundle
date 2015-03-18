<?php

namespace Performer\RerUserBundle\Tests\Listener;

use Performer\RerUserBundle\Listener\AuthenticationExceptionListener;
use PHPUnit_Framework_TestCase;

/**
 * @group unit
 */
class AuthenticationExceptionListenerTest extends PHPUnit_Framework_TestCase
{
    public function testOnKernelExceptionNoRedirect()
    {
        $router = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent')
            ->disableOriginalConstructor()->getMock();
        $exception = $this->getMockBuilder('Exception')->disableOriginalConstructor()->getMock();

        $event->expects($this->once())->method('getException')->will($this->returnValue($exception));
        $event->expects($this->never())->method('setResponse');
        $router->expects($this->never())->method('generate');

        $listener = new AuthenticationExceptionListener($router);
        $listener->onKernelException($event);
    }

    public function testOnKernelExceptionRedirect()
    {
        $router = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent')
            ->disableOriginalConstructor()->getMock();
        $exception = $this->getMockBuilder('Symfony\Component\Security\Core\Exception\AuthenticationException')
            ->disableOriginalConstructor()->getMock();

        $event->expects($this->once())->method('getException')->will($this->returnValue($exception));
        $event->expects($this->once())->method('setResponse');
        $router->expects($this->once())->method('generate')->will($this->returnValue('/login'));

        $listener = new AuthenticationExceptionListener($router);
        $listener->onKernelException($event);
    }
}
