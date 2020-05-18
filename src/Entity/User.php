<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;
    /**
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;
    /**
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $lastName;
    /**
     * @ORM\Column(name="phone_number", type="string", length=255, nullable=true)
     */
    protected $phoneNumber;
    /**
     * @ORM\Column(name="location", type="string", length=255, nullable=true)
     */
    protected $location;
    /**
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    protected $website;
    /**
     * @ORM\Column(type="string", name="provider_id", nullable=true)
     */
    protected $providerId;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="user", orphanRemoval=true)
     */
    private Collection $productList;

    public function __construct()
    {
        parent::__construct();
        $this->productList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }


    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite($website): void
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getProviderId()
    {
        return $this->providerId;
    }

    /**
     * @param mixed $providerId
     */
    public function setProviderId($providerId): void
    {
        $this->providerId = $providerId;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProductList(): Collection
    {
        return $this->productList;
    }

    /**
     * @param Product $productList
     * @return $this
     */
    public function addProductList(Product $productList): self
    {
        if (!$this->productList->contains($productList)) {
            $this->productList[] = $productList;
            $productList->setUser($this);
        }

        return $this;
    }

    /**
     * @param Product $productList
     * @return $this
     */
    public function removeProductList(Product $productList): self
    {
        if ($this->productList->contains($productList)) {
            $this->productList->removeElement($productList);
            // set the owning side to null (unless already changed)
            if ($productList->getUser() === $this) {
                $productList->setUser(null);
            }
        }

        return $this;
    }

}
