<?php

namespace OSEL\MusicsheetBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComposerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',                TextType::class)
            ->add('lastname',            TextType::class)
            ->add('dateBirth',           DateType::class, array(
                'widget' => 'text',
                'years' => range(date('Y') - 300, date('Y'))))
            ->add('dateDeath',           DateType::class, array(
                'widget' => 'text',
                'years' => range(date('Y') - 300, date('Y'))))
            ->add('save',               SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OSEL\MusicsheetBundle\Entity\Composer'
        ));
    }
}
