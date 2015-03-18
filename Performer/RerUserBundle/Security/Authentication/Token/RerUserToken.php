<?php

namespace Performer\RerUserBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class RerUserToken extends AbstractToken
{
    public $username;
    public $password;
    public $domain;

    /**
     * @param string $username
     * @param string $password
     * @param array  $roles
     */
    public function __construct($username, $password, $domain, array $roles = [])
    {
        $this->username = $username;
        $this->password = $password;
        $this->domain = $domain;

        parent::__construct($roles);

        // Se l'utente ha dei ruoli, considerarlo autenticato
        $this->setAuthenticated(count($roles) > 0);
    }

    /**
     * {@inheritDoc}
     */
    public function getCredentials()
    {
        return '';
    }
}
