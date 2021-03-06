<?php

/*
 * This class is copied and adapted from BeelabUserBundle
 * https://github.com/Bee-Lab/BeelabUserBundle
 */

namespace Performer\RerUserBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Performer\RerUserBundle\User\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * User manager
 */
class UserManager extends LightUserManager
{
    protected $security;
    protected $paginator;
    protected $dispatcher;

    /**
     * @param string                   $class
     * @param ObjectManager            $em
     * @param SecurityContextInterface $security
     * @param PaginatorInterface       $paginator
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct($class, ObjectManager $em, SecurityContextInterface $security, PaginatorInterface $paginator, EventDispatcherInterface $dispatcher)
    {
        parent::__construct($class, $em);
        $this->security = $security;
        $this->paginator = $paginator;
        $this->dispatcher = $dispatcher;
    }

    /**
     * List of users
     *
     * @param  int                                                 $page
     * @param  int                                                 $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getList($page = 1, $limit = 20)
    {
        $qb = $this->repository->createQueryBuilder('u');

        return $this->paginator->paginate($qb, $page, $limit);
    }

    /**
     * Find user by email
     *
     * @param  string        $email
     * @return UserInterface
     */
    public function find($email)
    {
        return $this->repository->findOneByEmail($email);
    }

    /**
     * Find user by id
     *
     * @param  integer       $id
     * @return UserInterface
     */
    public function get($id)
    {
        $user = $this->repository->find($id);
        if (empty($user)) {
            throw new NotFoundHttpException(sprintf('Cannot find user with id %u', $id));
        }

        return $user;
    }

    /**
     * Delete user
     *
     * @param UserInterface $user
     * @param boolean       $flush
     */
    public function delete(UserInterface $user, $flush = true)
    {
        if ($user->hasRole('ROLE_SUPER_ADMIN') && !$this->security->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('You cannot delete a super admin user.');
        }
        if ($this->security->getToken()->getUser() == $user) {
            throw new AccessDeniedException('You cannot delete your user.');
        }
        $this->em->remove($user);
        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Manual authentication
     *
     * @param UserInterface $user
     * @param Request       $request
     * @param string        $firewall firewall name (see your security.yml config file)
     * @param boolean       $logout   wether to logout before login
     */
    public function authenticate(UserInterface $user, Request $request, $firewall = 'main', $logout = false)
    {
        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());
        if ($logout) {
            $request->getSession()->invalidate();
        }
        $this->security->setToken($token);
        $event = new InteractiveLoginEvent($request, $token);
        $this->dispatcher->dispatch('security.interactive_login', $event);
    }
}
