<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Transaction;
use App\Enum\TransactionType as EnumTransactionType;

use PhpParser\Builder\Enum_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount',null, [ 'required' => true,])
            ->add('type', EnumType::class, [
                'class' => EnumTransactionType::class,
                'required' => true,
                'choice_label' => fn (EnumTransactionType $type) => $type->value,
            ])
            ->add('date',null, ['required' => true,])
            ->add('description')
            ->add('category', EntityType::class, [
                'required' => true,
                'class' => Category::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
