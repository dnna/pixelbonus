<?php

namespace Pixelbonus\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);

        $builder
            ->remove('plainPassword')
            ->add('name', 'text', array('label' => 'form.name', 'translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'form.name')))
            ->add('surname', 'text', array('label' => 'form.surname', 'translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'form.surname')))
            ->add('organization', null, array('label' => 'form.organization', 'translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'form.organization')))
            ->add('department', null, array('label' => 'form.department', 'translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'form.department')))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'form.email')))
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'form.username')))
            ->add('plainPassword', 'password', array(
                'translation_domain' => 'FOSUserBundle',
                'label' => 'form.password',
                'invalid_message' => 'fos_user.password.mismatch',
                'pattern' => '.{5,}',
                'attr' => array('placeholder' => 'form.password')
            ))
        ;
    }

    public function getName()
    {
        return 'pixelbonus_user_registration';
    }
}
