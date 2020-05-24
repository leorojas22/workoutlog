<?php

namespace App\Form;

use App\Entity\Exercise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ExerciseType extends BaseAbstractType
{
    protected $className = Exercise::class;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'constraints' => [
                    new NotBlank(['message' => 'Exercise name may not be blank.'])
                ]
            ])
            ->add('statTemplate', TextareaType::class, [
                'empty_data' => ''
            ])
            ->add('sumStat', TextType::class, [
                'empty_data' => ''
            ])
        ;
    }
}
