<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProductType
 * @package App\Form
 */
class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('name', TextType::class, [
                'help' => 'Введите название товара',
                'required' => true,
                'attr' => [
                    'maxlength' => 255,
                ],
            ])
            ->add('description', TextareaType::class, [
                'help' => 'Введите описание товара',
                'required' => false,
            ])
            ->add('price', NumberType::class, [
                'help' => 'Введите дробное число больше нуля',
                'required' => true,
                'scale' => 10,
                'attr' => [
                    'type' => 'number',
                    'min' => '0.00',
                    'max' => '99999999.99',
                    'step' => '0.01',
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
