<?php
namespace RCS\Bundle\DataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TemperatureType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'temperature';
    }

}
