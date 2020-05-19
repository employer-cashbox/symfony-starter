<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ORM\Table(name="transaction")
 */
class Transaction
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
     *     pattern = "/^[0-9A-z-._]+$/i",
     *     htmlPattern = "^[0-9A-z-_]+$",
     *     message = "В названии транзакции присутствуют не допустимые символы! (Доступны только: латинские буквы, цифры, дефис, точка и знак подчеркивания)"
     * )
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Название транзакции не должно превышать более {{ limit }} символов",
     *      allowEmptyString = false
     * )
     */
    private ?string $name = null;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="transactionList")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Product $product;

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
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return $this
     */
    public function setProduct(?Product $product = null): self
    {
        $this->product = $product;

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
