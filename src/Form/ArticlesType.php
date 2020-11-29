<?php

namespace App\Form;

// use FOS\CKEditorBundle\Form\type\CKEditorType;
use App\Entity\Articles;
use App\Entity\MotsClesArticles;
use App\Entity\CategoriesArticles;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('contenu')
            // ->add('contenu', CKEditorType::class)
            ->add('featured_image')
            
            // ->add('slug')
            ->add('motsClesArticles', EntityType::class, [
                'class' => MotsClesArticles::class,
                'label' => 'Mots-Clés',
                'multiple' => true,
                'expanded' => true,
                
                
            ])
            ->add('categoriesArticles', EntityType::class, [
                'class' => CategoriesArticles::class,
                'label' => 'Catégories',
                'multiple' => true,
                'expanded' => true,
                
                
            ])
            // ->add('created_at')
            // ->add('updated_at')
            // ->add('users')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
