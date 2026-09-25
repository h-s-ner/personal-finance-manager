<?php

namespace App\Form;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\ExpressionLanguage\Node\NullCoalesceNode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryDeleteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['transactionsCount'] > 0)
        {
            $builder->add('replacementCategory', EntityType::class, [
            'class' => Category::class,
            'label' => 'Please select a category to reassign these transactions to:',
            'choice_label' => 'name',
            'placeholder' => 'Select a category',
            'query_builder' => function (EntityRepository $repository) use ($options) {
                return $repository->createQueryBuilder('c')
                    ->where('c != :category')
                    ->setParameter('category', $options['category'])
                    ->orderBy('c.name', 'ASC');
                },
            ]);
        }
        $builder->add('delete',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'category' => null,
            'csrf_token_id' => 'delete_category',
            'transactionsCount' => 0,
        ]);
    }
}
