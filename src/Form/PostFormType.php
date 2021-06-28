<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            // ->add('slug')
            // ->add('content', TextareaType::class, [
            ->add('content', CKEditorType::class, [
                'label' => 'Content',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('image', TextType::class, [
                'label' => 'Image',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            // ->add('createdAt')
            // ->add('active')
            // ->add('user')
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'label' => 'Categories',
                    'attr' => [
                        'class' => 'form-select',
                    ],
                ])
                // ->add('Send', SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
