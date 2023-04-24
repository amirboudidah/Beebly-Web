<?php

namespace App\Form;

use App\Entity\Detailslivraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class DetailslivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('adresselivraison')


        ;

       /* ->add('etatlivrasion', ChoiceType::class, [
        'choices'  => [
            'EnRoute' => null,
            'Livré' => true,
            'NonDisponible' => false,
        ],])*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Detailslivraison::class,
        ]);
    }
}
