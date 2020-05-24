<?php

namespace App\Form;

use App\Entity\WorkoutExerciseSet;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class WorkoutExerciseSetType extends BaseAbstractType
{
    protected $className = WorkoutExerciseSet::class;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('statNotes', TextareaType::class, [
                'empty_data' => ''
            ])
            ->add('notes', TextareaType::class, [
                'empty_data' => ''
            ]);
        ;
    }

}
