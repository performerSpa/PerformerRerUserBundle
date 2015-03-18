<?php

namespace Performer\RerUserBundle\Tests\Security\Firewall;

use Performer\RerUserBundle\Security\Firewall\RerUserListener;
use PHPUnit_Framework_TestCase;

/**
 * @group unit
 */
class RerUserListenerTest extends PHPUnit_Framework_TestCase
{
    protected $authManager;
    protected $httpUtils;
    protected $sut;

    public function setUp()
    {
        $this->authManager = $this
            ->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface');
        $security = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        $sessionAuthStrategy = $this->getMock('Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface');
        $this->httpUtils = $this->getMock('Symfony\Component\Security\Http\HttpUtils');
        $successHandler = $this->getMock('Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface');
        $failureHandler = $this->getMock('Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface');

        $this->sut = new RerUserListener($security, $this->authManager, $sessionAuthStrategy, $this->httpUtils, 'key',
                                         $successHandler, $failureHandler);
    }

    public function testAttemptAuthentication()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()->getMock();
        $session = $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
            ->disableOriginalConstructor()->getMock();

        $this->httpUtils->expects($this->once())->method('checkRequestPath')->will($this->returnValue(true));
        $event->expects($this->once())->method('getRequest')->will($this->returnValue($request));
        $this->authManager->expects($this->once())->method('authenticate')
            ->will($this->returnValue(null));
        $session->expects($this->once())->method('set');
        $request->expects($this->once())->method('hasSession')->will($this->returnValue(true));
        $request->expects($this->once())->method('hasPreviousSession')->will($this->returnValue(true));
        $request->expects($this->once())->method('getSession')->will($this->returnValue($session));

        $this->assertNull($this->sut->handle($event));
    }
}
