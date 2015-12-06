<?php

namespace Pixelbonus\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QrSetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('quantity', null, array('required' => true, 'label' => 'qrsets.form.quantity', 'attr' => array('placeholder' => 'qrsets.form.quantity')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => 'Pixelbonus\SiteBundle\Entity\QrSet',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'qrset';
    }
}