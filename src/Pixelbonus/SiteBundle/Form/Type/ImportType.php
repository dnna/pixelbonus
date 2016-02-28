<?php

namespace Pixelbonus\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('courseFile', 'file', array('required' => true, 'label' => 'courses.form.import', 'attr' => array('placeholder' => 'courses.form.import')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'import';
    }
}