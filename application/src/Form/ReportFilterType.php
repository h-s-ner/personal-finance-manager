<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        match ($options['period']) {
            'day' => $builder->add('date', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ]),

            'month' => $builder->add('month', ChoiceType::class, [
                'choices' =>
                [
                    'Jan' => 'January',
                    'Feb' => 'Feburary',
                    'Mar' => 'March',
                    'Apr' => 'April',
                    'May' => 'May',
                    'Jun' => 'June',
                    'Jul' => 'July',
                    'Aug' => 'August',
                    'Sep' => 'September',
                    'Oct' => 'October', 
                    'Nov' => 'November',
                    'Dec' => 'December'
                ],
               
           
                'data' => date('F'),
            ])
            ->add('year', ChoiceType::class, [
            'choices' => array_combine(
                $options['years'],
                $options['years']
            ),
            'data' => (int) date('Y'),
        ]),

            'year' => $builder->add('year', ChoiceType::class, [
            'choices' => array_combine(
                $options['years'],
                $options['years']
            ),
            'data' => (int) date('Y'),
        ]),
        };
        $builder->add('submit',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'period' => 'day',
            'years' => []
        ]);

        $resolver->setAllowedValues('period', [
            'day',
            'month',
            'year',
        ]);
       
    }
}

