<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\RobokassaSettings;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * Class RobokassaSettingsRepository
 * @package App\Repository
 * @method RobokassaSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method RobokassaSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method RobokassaSettings[]    findAll()
 * @method RobokassaSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RobokassaSettingsRepository extends ServiceEntityRepository
{
    /**
     * RobokassaSettingsRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RobokassaSettings::class);
    }

    /**
     * Сохранение настроек robokassa
     * @param User  $user
     * @param array $robokassaSettingData
     * @return bool
     */
    public function save(User $user, array $robokassaSettingData)
    {
        $robocassaSetting = $user->getRobokassaSettings();
        if (!$robocassaSetting instanceof RobokassaSettings) {
            $robocassaSetting = new RobokassaSettings();
        }

        $robocassaSetting->setSiteIdentity($robokassaSettingData['siteIdentity']);
        $robocassaSetting->setPassword1($robokassaSettingData['password1']);
        $robocassaSetting->setPassword2($robokassaSettingData['password2']);
        $robocassaSetting->setHashCalculationAlgorithm($robokassaSettingData['hashCalculationAlgorithm']);
        $robocassaSetting->setInvoiceId($robokassaSettingData['invoiceId']);
        $robocassaSetting->setUser($user);

        try {
            $this->getEntityManager()->persist($robocassaSetting);
            $this->getEntityManager()->flush();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
