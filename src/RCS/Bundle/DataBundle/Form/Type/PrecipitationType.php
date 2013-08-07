<?php
namespace RCS\Bundle\DataBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrecipitationType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                1 => 'Clear',
                2 => 'Cloudy',
                3 => 'Hail',
                4 => 'Rain (measurable)',
                5 => 'Sleet',
                6 => 'Snow',
                7 => 'Trace',
            )
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
