<?php
namespace RCS\Bundle\DataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LandType extends AbstractType
{
    public static $CHOICES = array(
        1 => 'Agricultural',
        2 => 'Commercial',
        3 => 'Industrial',
        4 => 'Park',
        5 => 'Residential',
        6 => 'Other',
    );

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => LandType::$CHOICES
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'precipitation';
    }

}
