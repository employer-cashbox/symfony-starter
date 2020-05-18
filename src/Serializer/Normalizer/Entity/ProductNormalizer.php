<?php declare(strict_types=1);


namespace App\Serializer\Normalizer\Entity;


use App\Entity\Product;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class ProductNormalizer
 * @package App\Serializer\Normalizer\Entity
 */
class ProductNormalizer implements NormalizerInterface
{
    /**
     * @param Product $product
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($product, $format = null, array $context = []): array
    {
        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Product;
    }
}