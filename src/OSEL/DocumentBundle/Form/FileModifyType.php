<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 14.02.18
 * Time: 14:05
 */

namespace OSEL\DocumentBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileModifyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('originalName',       TextType::class)
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
            'data_class' => 'OSEL\DocumentBundle\Entity\File'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'osel_documentbundle_file';
    }


}
