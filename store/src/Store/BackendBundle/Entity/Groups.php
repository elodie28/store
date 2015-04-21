<?php

namespace Store\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;


/**
 * Class Groups
 * @ORM\Table(name="groups")
 * @ORM\Entity
 */
class Groups implements RoleInterface {


    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=300)
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=300, unique=true)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="Jeweler", mappedBy="groups")
     */
    private $jeweler;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jeweler = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Groups
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Groups
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Add jeweler
     *
     * @param \Store\BackendBundle\Entity\Jeweler $jeweler
     * @return Groups
     */
    public function addJeweler(\Store\BackendBundle\Entity\Jeweler $jeweler)
    {
        $this->jeweler[] = $jeweler;

        return $this;
    }

    /**
     * Remove jeweler
     *
     * @param \Store\BackendBundle\Entity\Jeweler $jeweler
     */
    public function removeJeweler(\Store\BackendBundle\Entity\Jeweler $jeweler)
    {
        $this->jeweler->removeElement($jeweler);
    }

    /**
     * Get jeweler
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJeweler()
    {
        return $this->jeweler;
    }

    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        return $this->role;
    }

}
