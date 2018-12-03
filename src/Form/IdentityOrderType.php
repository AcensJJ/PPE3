<?php

namespace App\Form;

use App\Entity\IdentityOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdentityOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('numTel')
            ->add('adresse')
            ->add('codePostal')
            ->add('ville')
            ->add('pays')
            ->add('email')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IdentityOrder::class,
        ]);
    }
}
