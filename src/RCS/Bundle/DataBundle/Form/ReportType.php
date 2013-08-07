<?php

namespace RCS\Bundle\DataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use RCS\Bundle\DataBundle\Form\Type\TemperatureType;
use RCS\Bundle\DataBundle\Form\Type\PrecipitationType;
use RCS\Bundle\DataBundle\Form\Type\LandType;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('latitude', null, array('required' => false))
            ->add('longitude', null, array('required' => false))
            ->add('timestamp', null, array('required' => true))
            ->add('participants', null, array('label' => 'Number of Participants at Monitoring Site', 'required' => true))
            ->add('precipitationDescription', new PrecipitationType(), array('label' => 'Describe recent precipitation at the site:', 'required' => false))
            ->add('landDescription', new LandType(), array('label' => 'Describe the land surrounding the site:', 'required' => false))
            ->add('turbidityNtu', null, array('label' => 'Turbidity (in NTU)', 'required' => false))
            ->add('temperatureC', new TemperatureType(), array('label' => 'Water Temperature', 'required' => false))
            ->add('dissolvedOxygenPpm', null, array('label' => 'Dissolved Oxygen (in ppm)', 'required' => false))
            ->add('ph', null, array('label' => 'pH', 'required' => false))
            ->add('airTemperatureC', new TemperatureType(), array('label' => 'Air Temperature', 'required' => false))
            ->add('rcsTestKitUse', null, array('label'  => 'Did you use the Water Monitoring test kit provided by RCS for any portion of the testing?', 'required' => false))
            ->add('followedQAProtocols', null, array('label'  => 'Did you follow standard Quality Assurance Protocols when monitoring?', 'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RCS\Bundle\DataBundle\Entity\Report'
        ));
    }

    public function getName()
    {
        return 'rcs_bundle_databundle_reporttype';
    }
}
