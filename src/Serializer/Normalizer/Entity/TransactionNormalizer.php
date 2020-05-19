<?php declare(strict_types=1);


namespace App\Serializer\Normalizer\Entity;


use App\Entity\Transaction;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class TransactionNormalizer
 * @package App\Serializer\Normalizer\Entity
 */
class TransactionNormalizer implements NormalizerInterface
{
    /**
     * @param Transaction $transaction
     * @param null        $format
     * @param array       $context
     * @return array
     */
    public function normalize($transaction, $format = null, array $context = []): array
    {
        return [
            'id' => $transaction->getId(),
            'name' => $transaction->getName(),
        ];
    }

    /**
     * @param mixed $data
     * @param null  $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Transaction;
    }
}