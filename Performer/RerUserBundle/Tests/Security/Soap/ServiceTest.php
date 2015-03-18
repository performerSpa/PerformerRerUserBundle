<?php

namespace Performer\RerUserBundle\Tests\Security\Soap;

use Performer\RerUserBundle\Security\Soap\Service;
use PHPUnit_Framework_TestCase;
use SoapFault;

/**
 * @group unit
 */
class ServiceTest extends PHPUnit_Framework_TestCase
{
    protected $service;
    protected $hashSoapClient;
    protected $authSoapClient;

    public function __construct()
    {
        $this->hashSoapClient = $this->getMockBuilder('SoapClient')->disableOriginalConstructor()
            ->setMethods(['ComputeSaltedHash'])->getMock();
        $this->authSoapClient = $this->getMockBuilder('SoapClient')->disableOriginalConstructor()
            ->setMethods(['Login'])->getMock();

        $this->service = new Service('', '', $this->hashSoapClient, $this->authSoapClient);
    }

    public function testLogin()
    {
        $result = $this->getMock('StdClass');
        $result->ComputeSaltedHashResult = 'hash...';
        $result->LoginResult = 'OK';
        $this->hashSoapClient->expects($this->once())->method('ComputeSaltedHash')->will($this->returnValue($result));
        $this->authSoapClient->expects($this->once())->method('Login')->will($this->returnValue($result));

        $this->assertEquals('OK', $this->service->login('pippo', 'pass', 'mondo'));
    }

    /**
     * @expectedException        RuntimeException
     * @expectedExceptionMessage hash error
     */
    public function testLoginHashException()
    {
        $this->hashSoapClient->expects($this->once())->method('ComputeSaltedHash')
            ->will($this->throwException(new SoapFault('42', 'hash error')));

        $this->service->login('pippo', 'pass', 'mondo');
    }

    /**
     * @expectedException        RuntimeException
     * @expectedExceptionMessage auth error
     */
    public function testLoginAuthException()
    {
        $result = $this->getMock('StdClass');
        $result->ComputeSaltedHashResult = 'hash...';
        $this->hashSoapClient->expects($this->once())->method('ComputeSaltedHash')->will($this->returnValue($result));
        $this->authSoapClient->expects($this->once())->method('Login')
            ->will($this->throwException(new SoapFault('42', 'auth error')));

        $this->service->login('pippo', 'pass', 'mondo');
    }
}
