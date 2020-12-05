<?php

namespace App\Form;

use App\Entity\MotsClesArticles;
use App\Entity\MotsCles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MotsClesArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('mots_cles')
            ->add('mots_cles' , EntityType::class, [
                'class' => MotsCles::class,
                'label' => 'Mots-Clés',
                'multiple' => true,
                'expanded' => true,
                ])
            ->add('articles')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MotsClesArticles::class,
        ]);
    }
}
