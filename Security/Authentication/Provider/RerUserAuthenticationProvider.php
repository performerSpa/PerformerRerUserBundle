<?php

namespace Performer\RerUserBundle\Security\Authentication\Provider;

use Performer\RerUserBundle\Security\Authentication\Token\RerUserToken;
use Performer\RerUserBundle\Security\Soap\Service;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * RerUserAuthenticationProvider
 */
class RerUserAuthenticationProvider implements AuthenticationProviderInterface
{
    private $service;
    private $userProvider;

    /**
     * @param UserProviderInterface $userProvider
     * @param Service               $service
     */
    public function __construct(UserProviderInterface $userProvider, Service $service)
    {
        $this->service = $service;
        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(TokenInterface $token)
    {
        try {
            $user = $this->userProvider->loadUserByUsername($token->username);
        } catch (UsernameNotFoundException $e) {
            throw new BadCredentialsException('Utente non valido.', $e->getCode(), $e);
        }
        if ($this->wsRerValidate($token->username, $token->password, $token->domain)) {
            $authenticatedToken = new RerUserToken($token->getUsername(), $token->password, $token->domain, $user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof RerUserToken;
    }

    /**
     * Richiama il Web Service della RER per la validazione dell'utente
     */
    protected function wsRerValidate($username, $password, $domain)
    {
        try {
            $loginResult = $this->service->login($username, $password, $domain);
        } catch (\RuntimeException $e) {
            throw new AuthenticationException('Errore comunicazione Web Service autenticazione.', $e->getCode(), $e);
        }
        switch ($loginResult) {
            case 'Riuscito':
                return true;
            case 'PasswordScaduta':
                throw new BadCredentialsException('Password scaduta.');
            case 'PasswordDaCambiare':
                throw new BadCredentialsException('Password da cambiare.');
            case 'LoginFallito':
                throw new BadCredentialsException('Autenticazione fallita.');
        }
        throw new AuthenticationException('Errore generico di autenticazione.');
    }
}
