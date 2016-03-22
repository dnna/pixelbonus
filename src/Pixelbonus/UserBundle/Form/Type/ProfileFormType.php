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
            ), 'label' => 'qr.prefered_grading_model', 'attr' => array('help' => 'qr.prefered_grading_model_help')))
            ->add('gradeMultiplier', null, array('translation_domain' => 'FOSUserBundle', 'label' => 'profile.edit.grade_multiplier', 'attr' => array('placeholder' => 'profile.edit.grade_multiplier', 'help' => 'profile.edit.grade_multiplier_help')))
            ->add('maxGrade', null, array('translation_domain' => 'FOSUserBundle', 'label' => 'profile.edit.max_grade', 'attr' => array('placeholder' => 'profile.edit.max_grade')))
            ->add('minGrade', null, array('translation_domain' => 'FOSUserBundle', 'label' => 'profile.edit.min_grade', 'attr' => array('placeholder' => 'profile.edit.min_grade')))
            ->remove('current_password');
        ;
    }

    public function getName()
    {
        return 'pixelbonus_user_profile';
    }
}
