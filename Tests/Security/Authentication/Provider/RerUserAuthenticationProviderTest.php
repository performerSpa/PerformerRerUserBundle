<?php

namespace Performer\RerUserBundle\Tests\Security\Authentication\Provider;

use Performer\RerUserBundle\Security\Authentication\Provider\RerUserAuthenticationProvider;
use Performer\RerUserBundle\Test\UserStub;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * @group unit
 */
class RerUserAuthenticationProviderTest extends PHPUnit_Framework_TestCase
{
    protected $userProvider;
    protected $service;
    protected $sut;

    public function setUp()
    {
        $this->userProvider = $this->getMock('Symfony\Component\Security\Core\User\UserProviderInterface');
        $this->service = $this->getMockBuilder('Performer\RerUserBundle\Security\Soap\Service')
            ->disableOriginalConstructor()->getMock();
        $this->sut = new RerUserAuthenticationProvider($this->userProvider, $this->service);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function testAuthenticateUserNotFound()
    {
        $token = $this->getMockBuilder('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken')
            ->disableOriginalConstructor()->getMock();
        $token->username = 'pippo';
        $this->userProvider->expects($this->once())->method('loadUserByUsername')->with('pippo')
            ->will($this->throwException(new UsernameNotFoundException()));

        $this->sut->authenticate($token);
    }

    public function testAuthenticateSuccess()
    {
        $token = $this->getMockBuilder('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken')
            ->disableOriginalConstructor()->getMock();
        $user = new UserStub();

        $this->userProvider->expects($this->once())->method('loadUserByUsername')->will($this->returnValue($user));
        $this->service->expects($this->once())->method('login')->will($this->returnValue('Riuscito'));

        $this->assertInstanceOf('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken',
                                $this->sut->authenticate($token));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function testAuthenticatePasswordScaduta()
    {
        $token = $this->getMockBuilder('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken')
            ->disableOriginalConstructor()->getMock();
        $user = new UserStub();

        $this->userProvider->expects($this->once())->method('loadUserByUsername')->will($this->returnValue($user));
        $this->service->expects($this->once())->method('login')->will($this->returnValue('PasswordScaduta'));

        $this->assertInstanceOf('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken',
                                $this->sut->authenticate($token));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function testAuthenticatePasswordDaCambiare()
    {
        $token = $this->getMockBuilder('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken')
            ->disableOriginalConstructor()->getMock();
        $user = new UserStub();

        $this->userProvider->expects($this->once())->method('loadUserByUsername')->will($this->returnValue($user));
        $this->service->expects($this->once())->method('login')->will($this->returnValue('PasswordDaCambiare'));

        $this->assertInstanceOf('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken',
                                $this->sut->authenticate($token));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function testAuthenticateFallita()
    {
        $token = $this->getMockBuilder('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken')
            ->disableOriginalConstructor()->getMock();
        $user = new UserStub();

        $this->userProvider->expects($this->once())->method('loadUserByUsername')->will($this->returnValue($user));
        $this->service->expects($this->once())->method('login')->will($this->returnValue('LoginFallito'));

        $this->assertInstanceOf('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken',
                                $this->sut->authenticate($token));
    }

    /**
     * @expectedException        \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @expectedExceptionMessage Errore generico di autenticazione.
     */
    public function testAuthenticateErroreSoap()
    {
        $token = $this->getMockBuilder('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken')
            ->disableOriginalConstructor()->getMock();
        $user = new UserStub();

        $this->userProvider->expects($this->once())->method('loadUserByUsername')->will($this->returnValue($user));
        $this->service->expects($this->once())->method('login')->will($this->returnValue('unexpected'));

        $this->assertInstanceOf('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken',
                                $this->sut->authenticate($token));
    }

    /**
     * @expectedException        \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @expectedExceptionMessage Errore comunicazione Web Service autenticazione.
     */
    public function testAuthenticateEccezioneSoap()
    {
        $token = $this->getMockBuilder('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken')
            ->disableOriginalConstructor()->getMock();
        $user = new UserStub();

        $this->userProvider->expects($this->once())->method('loadUserByUsername')->will($this->returnValue($user));
        $this->service->expects($this->once())->method('login')->will($this->throwException(new \RuntimeException()));

        $this->assertInstanceOf('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken',
                                $this->sut->authenticate($token));
    }

    public function testSupportsTrue()
    {
        $token = $this->getMockBuilder('Performer\RerUserBundle\Security\Authentication\Token\RerUserToken')
            ->disableOriginalConstructor()->getMock();

        $this->assertTrue($this->sut->supports($token));
    }

    public function testSupportsFalse()
    {
        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\AbstractToken')
            ->disableOriginalConstructor()->getMock();

        $this->assertFalse($this->sut->supports($token));
    }
}
