<?php

namespace OSEL\DocumentBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DirectoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('role', EntityType::class, array(
                'class'     => 'OSELUserBundle:Roles',
                'choice_label'  => 'name',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('s')
                        ->where('s.role != :role')
                        ->setParameter('role', 'ROLE_USER')
                        ->orderBy('s.rank', 'ASC');
                },
                'multiple'  => false))
            ->add('save',           SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OSEL\DocumentBundle\Entity\Directory'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'osel_documentbundle_directory';
    }


}
