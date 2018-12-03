<?php

namespace App\Form;

use App\Entity\ModeLivraison;
use App\Entity\LivraisonOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse')
            ->add('modeLivraison', EntityType::class, array(
                // looks for choices from this entity
                'class' => ModeLivraison::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'mode',
                'placeholder'   => 'Choisir le mode de livraison',
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LivraisonOrder::class,
        ]);
    }
}
