<?php

namespace Performer\RerUserBundle\Security\Soap;

use RuntimeException;
use SoapClient;
use SoapFault;

/**
 * Servizio per interfacciarsi con il WebService della Regione
 */
class Service
{
    /**
     * @var string
     */
    protected $idApp;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var SoapClient
     */
    protected $hashSoapClient;

    /**
     * @var SoapClient
     */
    protected $authSoapClient;

    /**
     * @param string     $idApp
     * @param string     $salt
     * @param SoapClient $hashSoapClient
     * @param SoapClient $authSoapClient
     */
    public function __construct($idApp, $salt, SoapClient $hashSoapClient, SoapClient $authSoapClient)
    {
        $this->idApp = $idApp;
        $this->salt = $salt;
        $this->hashSoapClient = $hashSoapClient;
        $this->authSoapClient = $authSoapClient;
    }

    /**
     * @param  string $username
     * @param  string $password
     * @param  string $dominio
     * @return string
     */
    public function login($username, $password, $dominio)
    {
        $saltedHash = $this->wsHash($username, $password, $dominio);
        $parametri = [
            'IDApplicazione' => $this->idApp,
            'domain'         => $dominio,
            'username'       => $username,
            'password'       => $password,
            'saltedHash'     => $saltedHash,
        ];

        return $this->wsLogin($parametri);
    }

    /**
     * @param  string           $username
     * @param  string           $password
     * @param  string           $dominio
     * @return string
     * @throws RuntimeException
     */
    private function wsHash($username, $password, $dominio)
    {
        $username = utf8_encode($username);
        $parametri = [
            'salt'      => $this->salt,
            'parametri' => $this->idApp . $dominio . $username . $password,
        ];
        try {
            $result = $this->hashSoapClient->ComputeSaltedHash($parametri);

            return $result->ComputeSaltedHashResult;
        } catch (SoapFault $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param  array            $parametri
     * @return string
     * @throws RuntimeException
     */
    private function wsLogin(array $parametri)
    {
        try {
            $login = $this->authSoapClient->Login($parametri);

            return $login->LoginResult;
        } catch (SoapFault $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
