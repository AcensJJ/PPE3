<?php

namespace App\Form;

use App\Entity\LivraisonUser;
use App\Entity\ModeLivraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LivraisonUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse', TextType::class)
            // ->add('modeLivraison', EntityType::class, array(
            //     // looks for choices from this entity
            //     'class' => ModeLivraison::class,

            //     'choice_label' => 'mode',
            
            //     // used to render a select box, check boxes or radios
            //     // 'multiple' => true,
            //     // 'expanded' => true,
            // ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LivraisonUser::class,
        ]);
    }
}