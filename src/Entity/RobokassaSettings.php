<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\RobokassaSettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RobokassaSettings
 * @package App\Entity
 * @ORM\Entity(repositoryClass=RobokassaSettingsRepository::class)
 */
class RobokassaSettings
{
    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * Идентификатор магазина в ROBOKASSA
     * @var string|null
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern = "/^[0-9A-z-_.]+$/i",
     *     htmlPattern = "^[0-9A-z-_.]+$",
     *     message = "В идентификаторе магазина на ROBOKASSA введены не допустимые символы!
    (Доступны только: латинские буквы, цифры, точка, дефис и знак подчеркивания)"
     * )
     */
    private ?string $siteIdentity = null;

    /**
     * Пароль #1
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private ?string $password1 = null;

    /**
     * Пароль #2
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private ?string $password2 = null;

    /**
     * Алгоритм расчета хеша
     * @var string|null
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern = "/^MD5$/i",
     *     htmlPattern = "^MD5$",
     *     message = "К сожалению наш сайт поддерживает только MD5 алгоритм подсчета хеша! Уже работаем над поддержкой
    всех остальных."
     * )
     */
    private ?string $hashCalculationAlgorithm = null;

    /**
     * Номер счета в магазине
     * @var string|null
     * @ORM\Column(type="string", length=20, options={"fixed" = true})
     * @Assert\Regex(
     *     pattern = "/^\d{20}$/i",
     *     htmlPattern = "^\d{20}$",
     *     message = "Номер счета должен состоять из 20 чисел."
     * )
     */
    private ?string $invoiceId = null;

    /**
     * Пользователь зарегистрированный в системе
     * @var User|null
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="robokassaSettings", cascade={"persist", "remove"})
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
    public function getSiteIdentity(): ?string
    {
        return $this->siteIdentity;
    }

    /**
     * @param string $siteIdentity
     * @return $this
     */
    public function setSiteIdentity(string $siteIdentity): self
    {
        $this->siteIdentity = $siteIdentity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword1(): ?string
    {
        return $this->password1;
    }

    /**
     * @param string $password1
     * @return $this
     */
    public function setPassword1(string $password1): self
    {
        $this->password1 = $password1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword2(): ?string
    {
        return $this->password2;
    }

    /**
     * @param string $password2
     * @return $this
     */
    public function setPassword2(string $password2): self
    {
        $this->password2 = $password2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHashCalculationAlgorithm(): ?string
    {
        return $this->hashCalculationAlgorithm;
    }

    /**
     * @param string $hashCalculationAlgorithm
     * @return $this
     */
    public function setHashCalculationAlgorithm(string $hashCalculationAlgorithm): self
    {
        $this->hashCalculationAlgorithm = $hashCalculationAlgorithm;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInvoiceId(): ?string
    {
        return $this->invoiceId;
    }

    /**
     * @param string $invoiceId
     * @return $this
     */
    public function setInvoiceId(string $invoiceId): self
    {
        $this->invoiceId = $invoiceId;

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
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
