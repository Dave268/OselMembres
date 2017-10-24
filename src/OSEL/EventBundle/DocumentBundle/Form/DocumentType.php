<?php

namespace OSEL\DocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class DocumentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', EntityType::class, array(
                'class'     => 'OSELDocBundle:YearDocument',
                'choice_label'  => 'year',
                'multiple'  => false))
            ->add('category', EntityType::class, array(
                'class'     => 'OSELDocBundle:CategoryDocument',
                'choice_label'  => 'category',
                'multiple'  => false))
            ->add('role', EntityType::class, array(
                'class'     => 'OSELUserBundle:Roles',
                'choice_label'  => 'role',
                'multiple'  => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.rank <= :rank')
                        ->setParameter('rank', 500)
                        ->orderBy('a.rank', 'DESC');
                }))
            ->add('save',               SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OSEL\DocBundle\Entity\Document'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'osel_DocBundle_document';
    }


}
