<?php

namespace Pixelbonus\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('name', null, array('required' => true, 'label' => 'courses.form.name', 'attr' => array('placeholder' => 'courses.form.name')))
            ->add('enrolledParticipants', 'number', array('required' => false, 'label' => 'courses.form.enrolledParticipants', 'invalid_message' => 'This field must be a number', 'attr' => array('placeholder' => 'courses.form.enrolledParticipants')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => 'Pixelbonus\SiteBundle\Entity\Course',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'course';
    }
}