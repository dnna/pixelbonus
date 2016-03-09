<?php
namespace Pixelbonus\UserBundle\Form\Type;

use Pixelbonus\SiteBundle\Form\Type\DogType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('preferredGradingModel', 'choice', array('required' => true, 'choices' => array(
                'curved_grading' => 'qr.curved_grading',
                'ranking' => 'qr.ranking',
            )))
            ->add('gradeMultiplier', null, array('translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'Grade Multiplier', 'help' => 'profile.edit.grade_multiplier_help')))
            ->add('maxGrade', null, array('translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'Max Grade')))
            ->add('minGrade', null, array('translation_domain' => 'FOSUserBundle', 'attr' => array('placeholder' => 'Min Grade')))
            ->remove('current_password');
        ;
    }

    public function getName()
    {
        return 'pixelbonus_user_profile';
    }
}
