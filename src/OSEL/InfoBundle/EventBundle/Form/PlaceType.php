<?php

namespace OSEL\EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PlaceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',      TextType::class)
            ->add('street',     TextType::class)
            ->add('number',     TextType::class)
            ->add('postal',     TextType::class)
            ->add('city',       TextType::class)
            ->add('country',    TextType::class)
            ->add('description',TextareaType::class, array('required' => false))
            ->add('phone',      TextType::class, array('required' => false))
            ->add('email',      EmailType::class, array('required' => false))
            ->add('comment',    TextareaType::class, array('required' => false))
            ->add('file',       FileType::class, array('required' => false))
            ->add('save',       SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OSEL\EventBundle\Entity\Place'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'osel_eventbundle_place';
    }


}
