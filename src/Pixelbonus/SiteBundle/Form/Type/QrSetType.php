<?php

namespace Pixelbonus\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QrSetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $tagOptions = array('multiple' => true, 'required' => true, 'label' => 'qr.form.tags', 'attr' => array('placeholder' => 'qr.form.tags'));
        $builder
            ->add('tagsFromString', 'choice', $tagOptions)
            ->add('quantity', null, array('required' => true, 'label' => 'qr.form.quantity', 'attr' => array('placeholder' => 'qr.form.quantity')))
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($tagOptions) {
           $data = $event->getData();
           if(is_array($data['tagsFromString'])) { $data=array_flip($data['tagsFromString']);
           } else { $data = array(); }
           $event->getForm()->add('tagsFromString', 'choice', array_merge($tagOptions, array('choices' => $data,)));
        });
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