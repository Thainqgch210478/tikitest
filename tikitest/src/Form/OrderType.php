<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address' , TextType::class)
            ->add('phonenumber', TextType::class)
            // ->add('status') 
            // ->add('paymentmethod',TextType::class)
            // ->add('date' , DateType::class)
            // [
            //     'label' => 'Published date',
            //     'view_timezone' => 'UTC',
            //     'widget' => 'choice',
                
            // ])
            // ->add('transportationmethod',TextType::class)
            // ->add('cusid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
