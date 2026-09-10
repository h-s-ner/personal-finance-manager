<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\TransactionType as EnumTransactionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TransactionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EnumType::class, [
                'class' => EnumTransactionType::class,
                'required' => false,
                'placeholder' => 'All Types',
                'choice_label' => fn (EnumTransactionType $type) => $type->value,
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'All Categories',
            ])
            ->add('fromDate',DateType::class,[
               
                'label' => 'From Date',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('tillDate',DateType::class,[
                'label' => 'Till Date',
                'required' => false,        
                'widget' => 'single_text',
            ])
            ->add('filter',SubmitType::class,[
                'label' => 'Filtern',
            ])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'method' => 'GET',
        ]);
    }
}
