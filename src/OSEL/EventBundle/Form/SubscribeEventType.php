<?php

namespace OSEL\EventBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Common\Collections\ArrayCollection;


class SubscribeEventType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transport',          ChoiceType::class, array( 'choices' => array('Voiture'=>'Voiture',
                    'Transport en commun'=>'Tranport en Commun',
                    'Cherche covoiturage'=> 'Cherche covoiturage')))
            ->add('city',      TextType::class, array(
                'required' => false))
            ->add('dateDeparture',      DateTimeType::class, array(
                    'widget' => 'single_text',
                    'required' => false))
            ->add('dateArrival',        DateTimeType::class, array(
                    'widget' => 'single_text',
                    'required' => false))
            ->add('presence',           CheckboxType::class, array('required' => false))
            ->add('subEvents', EntityType::class, array(
                    'class' => 'OSELEventBundle:SubEvents',
                    'query_builder' => function(EntityRepository $er){
                        return $er->createQueryBuilder('s')
                            ->leftJoin('s.event', 'c')
                            ->addSelect('c')
                            ->where('c.active = :active')
                            ->setParameter('active', true)
                            ->orderBy('s.startEvent', 'ASC');
                    },
                    'choice_label' => 'title',
                    'expanded' => true,
                    'multiple' => true))
            ->add('nbPlaces',       ChoiceType::class, array( 'choices' => array(
                    0, 1, 2, 3, 4, 5, 6, 7, 8, 9)))
            ->add('save',       SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OSEL\EventBundle\Entity\SubscribeEvent'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'osel_eventbundle_subscribeevent';
    }


}
