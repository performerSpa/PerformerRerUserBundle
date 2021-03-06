<?php

namespace Performer\RerUserBundle\Tests\Controller;

use Performer\RerUserBundle\Controller\AuthController;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @group unit
 */
class AuthControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $container;

    public function setUp()
    {
        $this->container = $this->getMockBuilder('Symfony\Component\DependencyInjection\Container')
            ->disableOriginalConstructor()->getMock();

        $this->controller = new AuthController();
        $this->controller->setContainer($this->container);
    }

    public function testLoginAuthenticated()
    {
        $securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $router = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');

        $this->container->expects($this->at(0))->method('get')->with('security.context')
            ->will($this->returnValue($securityContext));
        $this->container->expects($this->at(1))->method('get')->with('router')
            ->will($this->returnValue($router));
        $securityContext->expects($this->any())->method('isGranted')->with('IS_AUTHENTICATED_FULLY')
            ->will($this->returnValue(true));
        $router->expects($this->once())->method('generate')->will($this->returnValue('url'));

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse',
                                $this->controller->loginAction($request));
    }

    public function testLogin()
    {
        $securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->attributes = new ParameterBag(['_security.last_username' => 'user']);
        $session = $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface');

        $this->container->expects($this->any())->method('get')->with('security.context')
            ->will($this->returnValue($securityContext));
        $securityContext->expects($this->any())->method('isGranted')->with('IS_AUTHENTICATED_FULLY')
            ->will($this->returnValue(false));
        $request->expects($this->any())->method('getSession')->will($this->returnValue($session));
        $session->expects($this->at(1))->method('get')->with('_security.last_error')->will($this->returnValue('user'));
        $session->expects($this->at(0))->method('get')->with('_security.last_username')
            ->will($this->returnValue('user'));

        $this->assertEquals(['last_username' => 'user', 'error' => 'user'], $this->controller->loginAction($request));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testLogout()
    {
        $this->controller->logoutAction();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testLoginCheck()
    {
        $this->controller->loginCheckAction();
    }
}
