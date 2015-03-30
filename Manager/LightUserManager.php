<?php

/*
 * This class is copied and adapted from BeelabUserBundle
 * https://github.com/Bee-Lab/BeelabUserBundle
 */

namespace Performer\RerUserBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Performer\RerUserBundle\User\UserInterface;

/**
 * Light User manager
 */
class LightUserManager
{
    protected $className;
    protected $em;
    protected $repository;

    /**
     * @param string        $class
     * @param ObjectManager $em
     */
    public function __construct($class, ObjectManager $em)
    {
        $this->className = $class;
        $this->em = $em;
        $this->repository = $em->getRepository($class);
    }

    /**
     * Get new instance of User
     *
     * @return UserInterface
     */
    public function getInstance()
    {
        return new $this->className;
    }

    /**
     * Create new user
     *
     * @param UserInterface $user
     * @param boolean       $flush
     */
    public function create(UserInterface $user, $flush = true)
    {
        $this->em->persist($user);
        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Update existing user
     *
     * @param UserInterface $user
     * @param boolean       $flush
     */
    public function update(UserInterface $user, $flush = true)
    {
        if ($flush) {
            $this->em->flush();
        }
    }
}
