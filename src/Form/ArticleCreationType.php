<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\ArticlesCategories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleCreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('content',TextareaType::class,  ['required' => false] )
            ->add('category', EntityType::class, [
                'class' => ArticlesCategories::class,
                'choice_label' => 'name',
            ])
            ->add('image', ImageArticleType::class, [
                'label'=> false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
