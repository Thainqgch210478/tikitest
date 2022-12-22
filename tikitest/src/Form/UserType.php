<?php

namespace App\Form;

use App\Entity\UserDetail;
use PHPUnit\TextUI\XmlConfiguration\Logging\TestDox\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class)
            ->add('dob',DateType::class,
            [
                'label' => 'Date of birth ',
                
                'widget' => 'single_text',
                
            ])
            ->add('address',TextType::class)
            
            

            // ->add('userid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserDetail::class,
        ]);
    }
}
