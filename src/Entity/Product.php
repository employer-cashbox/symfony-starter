<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(
 *     name="product",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"name", "user_id", "is_deleted"})}
 * )
 * @UniqueEntity(
 *      fields={"name", "user", "isDeleted"},
 *      message="У Вас уже есть товар с таким названием."
 * )
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
     * Название товара
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern = "/^[0-9А-яЁёA-z-._ ]+$/i",
     *     htmlPattern = "^[0-9А-яЁёA-z-._ ]+$",
     *     message = "В названии товара введены не допустимые символы! (Доступны только: буквы, цифры, пробел, дефис,
     *     точка и знак подчеркивания )"
     * )
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Название товара не должно превышать более {{ limit }} символов",
     *      allowEmptyString = false
     * )
     */
    private ?string $name = null;

    /**
     * Описание товара
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private ?string $description = null;

    /**
     * Цена
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\Positive(
     *      message = "Значение должно быть положительным числом (больше нуля)",
     * )
     */
    private ?float $price = 0.00;

    /**
     * Удален ли продукт
     * @ORM\Column(type="boolean")
     * @var bool|null
     */
    private ?bool $isDeleted = false;

    /**
     * Пользователь зарегистрированный в системе
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="productList")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * Список транзакций по текущему продукту
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="transaction", orphanRemoval=true)
     */
    private Collection $transactionList;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->transactionList = new ArrayCollection();
        $this->isDeleted = false;
    }

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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description = null): self
    {
        $this->description = $description;

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
     * @return bool|null
     */
    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool|null $isDeleted
     * @return $this
     */
    public function setIsDeleted(?bool $isDeleted = false): self
    {
        $this->isDeleted = $isDeleted;

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

    /**
     * @return Collection|Transaction[]
     */
    public function getProductList(): Collection
    {
        return $this->transactionList;
    }

    /**
     * @param Transaction $transactionList
     * @return $this
     */
    public function addProductList(Transaction $transactionList): self
    {
        if (!$this->transactionList->contains($transactionList)) {
            $this->transactionList[] = $transactionList;
            $transactionList->setProduct($this);
        }

        return $this;
    }

    /**
     * @param Transaction $transactionList
     * @return $this
     */
    public function removeProductList(Transaction $transactionList): self
    {
        if ($this->transactionList->contains($transactionList)) {
            $this->transactionList->removeElement($transactionList);
            // set the owning side to null (unless already changed)
            if ($transactionList->getProduct() === $this) {
                $transactionList->setProduct(null);
            }
        }

        return $this;
    }
}
