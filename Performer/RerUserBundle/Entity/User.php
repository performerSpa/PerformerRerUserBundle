<?php

namespace Performer\RerUserBundle\Entity;

use Performer\RerUserBundle\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\MappedSuperclass
 * @UniqueEntity(fields={"username"}, groups={"create", "update"})
 */
abstract class User implements UserInterface, EquatableInterface, \Serializable
{
    /**
     * @var array
     */
    protected static $roleLabels = [
        'ROLE_ADMIN' => 'admin',
        'ROLE_USER'  => 'user',
    ];

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(unique=true)
     * @Assert\NotBlank(groups={"create", "update"})
     */
    protected $username;

    /**
     * @ORM\Column()
     * @Assert\NotBlank(groups={"create", "update"})
     */
    protected $domain;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles = [];

    /**
     * @ORM\Column(name="is_active", type="boolean", options={"default"=1})
     * @Assert\Type(type="bool", groups={"create", "update"})
     */
    protected $active = true;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     * @Assert\DateTime(groups={"create", "update"})
     */
    protected $lastLogin;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->username;
    }

    /**
     * @param  array $roles
     * @return User
     */
    public function setRoles(array $roles)
    {
        $this->roles = [];
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // we need to make sure to have at least one role
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
    }

    /**
     * @param  string $role
     * @return User
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * Never use this to check if this user has access to anything!
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g. $securityContext->isGranted('ROLE_USER');
     *
     * @param  string  $role
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * @param  string $role
     * @return User
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     * @return string
     */
    public function serialize()
    {
        return serialize([$this->id, $this->username]);
    }

    /**
     * @see \Serializable::unserialize()
     * @param string
     */
    public function unserialize($serialized)
    {
        list($this->id, $this->username) = unserialize($serialized);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param  string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set domain
     *
     * @param  string $domain
     * @return User
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set active
     *
     * @param  boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * IsActive
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param  \DateTime $time
     * @return User
     */
    public function setLastLogin(\DateTime $time = null)
    {
        $this->lastLogin = $time;

        return $this;
    }

    /**
     * Gets the last login time.
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonExpired()
    {
        return true;    // not implemented
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonLocked()
    {
        return true;    // not implemented
    }

    /**
     * @inheritDoc
     */
    public function isCredentialsNonExpired()
    {
        return true;    // not implemented
    }

    /**
     * @inheritDoc
     */
    public function isEnabled()
    {
        return $this->isActive();
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo(SymfonyUserInterface $user)
    {
        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * Get role labels
     *
     * @return array
     */
    public static function getRoleLabels()
    {
        return static::$roleLabels;
    }

    /**
     * Get role label
     *
     * @param  string $role
     * @return string
     */
    public function getRoleLabel($role)
    {
        return $role == 'ROLE_SUPER_ADMIN' ? 'super admin' : static::$roleLabels[$role];
    }

    /**
     * Get roles with labels
     *
     * @param  string $glue
     * @return string
     */
    public function getRolesWithLabel($glue = ', ')
    {
        $labels = [];
        foreach ($this->roles as $role) {
            $labels[] = $this->getRoleLabel($role);
        }

        return implode($glue, $labels);
    }

    /**
     * Necessaria solo per l'interfaccia
     */
    public function getPassword()
    {
    }

    /**
     * Necessaria solo per l'interfaccia
     */
    public function getSalt()
    {
    }
}
