<?php

namespace OSEL\MusicsheetBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MusicsheetType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',              TextType::class)
            ->add('year',               TextType::class)
            ->add('actif',              CheckboxType::class, array('required' => false))
            ->add('composer', EntityType::class, array(
                'class'     => 'OSELMusicsheetBundle:Composer',
                'choice_label'  => 'composer',
                'multiple'  => false))
            ->add('type', EntityType::class, array(
                'class'     => 'OSELMusicsheetBundle:SheetType',
                'choice_label'  => 'title',
                'multiple'  => false))
            ->add('uploadedFiles', FileType::class, array(
                'multiple' => true,
                'data_class'=> null,
                'required' => false
            ))
            ->add('parts', CollectionType::class, array(
                'entry_type'   => PartsType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false
            ))
            ->add('save',               SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OSEL\MusicsheetBundle\Entity\Musicsheet'
        ));
    }
}
