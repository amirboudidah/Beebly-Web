<?php

namespace App\Form;

use App\Entity\Propositionlivre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropositionlivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titrelivre')
            ->add('editon')
            ->add('dateproposition')
            ->add('descriptionetat')
            ->add('idclient')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Propositionlivre::class,
        ]);
    }
}
