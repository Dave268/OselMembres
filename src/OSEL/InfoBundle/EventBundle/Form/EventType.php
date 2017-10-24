<?php

namespace OSEL\EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',      TextType::class)
            ->add('dateStart',  DateTimeType::class, array(
                'widget' => 'single_text'))
            ->add('dateEnd',    DateTimeType::class, array(
        'widget' => 'single_text'))
            ->add('infos',      TextareaType::class)
            ->add('place', EntityType::class, array(
                'class'     => 'OSELEventBundle:Place',
                'choice_label'  => 'title',
                'multiple'  => false,
                'required' => true))
            ->add('subEvents', CollectionType::class, array(
                'entry_type'   => SubEventsType::class,
                'allow_add'    => true,
                'allow_delete' => true
            ))
            ->add('file',       FileType::class, array('required' => false))
            ->add('save',       SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OSEL\EventBundle\Entity\Event'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'osel_eventbundle_event';
    }


}
