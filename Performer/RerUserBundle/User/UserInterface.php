<?php

namespace Performer\RerUserBundle\User;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Interface used by User entity
 */
interface UserInterface extends AdvancedUserInterface
{
    /**
     * @param  string
     * @return boolean
     */
    public function hasRole($role);

    /**
     * @param \DateTime $date
     */
    public function setLastLogin(\DateTime $date);
}
