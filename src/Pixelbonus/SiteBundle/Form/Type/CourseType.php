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