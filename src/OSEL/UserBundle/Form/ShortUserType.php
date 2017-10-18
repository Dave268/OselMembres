<?php

namespace OSEL\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShortUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name',               TextType::class)
            ->add('email',              TextType::class)
            ->add('mobilephone',        TextType::class, array('required' => false))
            ->add('lastname',           TextType::class)
            ->add('groupes', EntityType::class, array(
                'class'     => 'OSELUserBundle:Groupes',
                'choice_label'  => 'groupe',
                'multiple'  => true,
				'required' => false))
            ->add('instruments', EntityType::class, array(
                'class'     => 'OSELUserBundle:Instruments',
                'choice_label'  => 'instrument',
                'multiple'  => true,
				'required' => false))
            ->add('profession',         TextType::class, array('required' => false))
            ->add('birthday',           BirthdayType::class, array('required' => false))
            ->add('save',               SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OSEL\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'osel_userbundle_user';
    }
}
