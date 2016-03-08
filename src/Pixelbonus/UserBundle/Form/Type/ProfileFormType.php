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
            ->add('gradeMultiplier')
            ->add('maxGrade')
            ->add('minGrade')
            ->remove('current_password');
        ;
    }

    public function getName()
    {
        return 'pixelbonus_user_profile';
    }
}
