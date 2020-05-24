<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class BaseAbstractType extends AbstractType
{
    protected $className;

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->className,

            // Handled by the auth token
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix() {
        return "";
    }
}
