<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('quantity', NumberType::class)
            ->add('description', TextareaType::class)
            ->add('price', TextType::class)
            ->add('image1', FileType::class, [
                'mapped'=>false,
                'label'=>'Select an image',
                'multiple'=>false
            ])
            ->add('image2', FileType::class, [
                'mapped'=>false,
                'label'=>'Select an image',
                'multiple'=>false])
            ->add('image3', FileType::class, [
                'mapped'=>false,
                'label'=>'Select an image',
                'multiple'=>false])
            ->add('categoryid', EntityType::class, [
                'class'=>Category::class,
                'choice_label'=>'name',
            ])
            ->add('brandid', EntityType::class, [
                'class'=>Brand::class,
                'choice_label'=>'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
