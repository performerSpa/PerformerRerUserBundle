<?php

/*
 * This class is copied and adapted from BeelabUserBundle
 * https://github.com/Bee-Lab/BeelabUserBundle
 */

namespace Performer\RerUserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auth controller
 */
class AuthController extends Controller
{
    /**
     * Login form
     *
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        return [
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $this->getLoginError($request),
        ];
    }

    /**
     * Logout (implemented by Symfony security system)
     *
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
     * Login check (implemented by Symfony security system)
     *
     * @Route("/login_check", name="login_check")
     * @Method("POST")
     */
    public function loginCheckAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * Get possible authentication error
     *
     * @param  Request $request
     * @return mixed   Exception or array
     */
    protected function getLoginError(Request $request)
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
            $request->getSession()->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        // see https://github.com/symfony/symfony/issues/837#issuecomment-3000155
        if ($error instanceof \Exception && !$error instanceof BadCredentialsException) {
            $this->get('logger')->log('error', $error->getMessage());
            $error = ['message' => 'Errore inatteso.'];
        }

        return $error;
    }
}
