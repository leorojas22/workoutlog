<?php
namespace App\Helper;

use App\Exception\AppException;
use Symfony\Component\Form\Form;
use App\Exception\MultipleErrorException;

class FormValidator
{
    public static function validate(Form $form)
    {
        if(!$form->isValid())
        {
            $errors = [];
            foreach($form->getErrors(true) as $error)
            {
                $errors[] = $error->getMessage();
            }

            if(count($errors) > 1)
            {
                throw new MultipleErrorException($errors);
            }

            throw new AppException($errors[0]);
        }

        return true;
    }
}
