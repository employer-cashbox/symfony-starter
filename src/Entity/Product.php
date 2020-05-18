<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern = "/^[0-9А-яЁёA-z-._ ]+$/i",
     *     htmlPattern = "^[0-9А-яЁёA-z-._ ]+$",
     *     message = "В названии товара введены не допустимые символы! (Доступны только: буквы, цифры, пробел, дефис, точка и знак подчеркивания )"
     * )
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Название продукта не должно превышать более {{ limit }} символов",
     *      allowEmptyString = false
     * )
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\Positive(
     *      message = "Значение должно быть положительным числом (больше нуля)",
     * )
     */
    private ?float $price = 0.00;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="productList")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name = null): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return $this
     */
    public function setPrice(?float $price = 0.00): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|UserInterface|null $user
     * @return $this
     */
    public function setUser(?User $user = null): self
    {
        $this->user = $user;

        return $this;
    }
}
