<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\RobokassaSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RobokassaType
 * @package App\Form
 */
class RobokassaType extends AbstractType
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
            ->add('siteIdentity', TextType::class, [
                'help' => 'Вы его придумали при создании магазина (Мои магазины -> Технические настройки -> Идентификатор магазина)',
                'attr' => [
                    'maxlength' => 255,
                ],
            ])
            ->add('password1', TextType::class, [
                'help' => 'Вы его придумали при создании магазина (Мои магазины -> Технические настройки -> Пароль #1)',
            ])
            ->add('password2', TextType::class, [
                'help' => 'Вы его придумали при создании магазина (Мои магазины -> Технические настройки -> Пароль #2)',
            ])
            ->add('hashCalculationAlgorithm', TextType::class, [
                'help' => 'Укажите этот алгоритм расчета хеша на robokassa (Мои магазины -> Технические настройки -> Алгоритм расчета хеша)',
                'disabled' => true,
                'attr' => [
                    'maxlength' => 255,
                    'value' => 'MD5',
                    'disabled' => true,
                ],
            ])
            ->add('invoiceId', TextType::class, [
                'help' => 'Расчетный счет из цифр, например 40817810099910004312',
                'attr' => [
                    'maxlength' => 20,
                    'pattern' => '\d{20}',
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RobokassaSettings::class,
        ]);
    }
}
