<?php

namespace RCS\Bundle\DataBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // remove email field
        $builder->remove('email');
    }

    public function getName()
    {
        return 'rcsdatabundle_user_registration';
    }
}