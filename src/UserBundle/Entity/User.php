<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SpoolerBundle\Entity\SpoolerItem;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends \FOS\UserBundle\Model\User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var ArrayCollection|Collection
     *
     * @ORM\OneToMany(targetEntity="SpoolerBundle\Entity\SpoolerItem", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $spoolerItems;

    /**
     * @var array
     *
     */
    static $allRoles = array(
      'Super Administrateur' => 'ROLE_SUPER_ADMIN',
      'Administrateur'       => 'ROLE_ADMIN',
      'Utilisateur standard' => 'ROLE_STD_USER',
    );

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get the highest Role
     *
     * @return mixed
     */
    public function getHighestRole(){
      $userRoles = $this->getRoles();
      foreach(User::$allRoles as $role):
        if(in_array($role, $userRoles)):
          return $role;
        endif;
      endforeach;
    }

    /**
     * Set All Hierarchical Roles
     *
     *
     * @param $highestRole
     *
     * @return User $this
     */
    public function addHierarchyRole($highestRole){
      $this->removeAllRoles();
      foreach (array_reverse(User::$allRoles) as $role):
        $this->addRole($role);
        if($role == $highestRole):
          break;
        endif;
      endforeach;
      return $this;
    }

    /**
     * Remove all role (typically before add new hierarchy roles)
     *
     * @return User $this
     */
    private function removeAllRoles(){
      foreach (User::$allRoles as $role):
        if($this->hasRole($role)):
          $this->removeRole($role);
        endif;
      endforeach;
      return $this;
    }

    /**
     * Add spoolerItem
     *
     * @param SpoolerItem $spoolerItem
     *
     * @return User
     */
    public function addSpoolerItem(SpoolerItem $spoolerItem)
    {
        $this->spoolerItems[] = $spoolerItem;

        return $this;
    }

    /**
     * Remove spoolerItem
     *
     * @param SpoolerItem $spoolerItem
     */
    public function removeSpoolerItem(SpoolerItem $spoolerItem)
    {
        $this->spoolerItems->removeElement($spoolerItem);
    }

    /**
     * Get spoolerItems
     *
     * @return Collection
     */
    public function getSpoolerItems()
    {
        return $this->spoolerItems;
    }
}
