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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;


class UserType extends AbstractType
{
    private $authorization;

    public function __construct(AuthorizationChecker $authorizationChecker)
    {
        $this->authorization = $authorizationChecker;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('username',           TextType::class, array('required' => false))
            ->add('name',               TextType::class)
            ->add('email',              TextType::class)
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
            ->add('actif',              CheckboxType::class, array('required' => false))
            ->add('profession',         TextType::class, array('required' => false))
            ->add('street',             TextType::class, array('required' => false))
            ->add('number',             TextType::class, array('required' => false))
            ->add('postal',             TextType::class, array('required' => false))
            ->add('city',               TextType::class, array('required' => false))
            ->add('country',            TextType::class, array('required' => false))
            ->add('phone',              TextType::class, array('required' => false))
            ->add('mobilephone',        TextType::class, array('required' => false))
            ->add('emergencylastname',  TextType::class, array('required' => false))
            ->add('emergencyname',      TextType::class, array('required' => false))
            ->add('emergencyrelation',  TextType::class, array('required' => false))
            ->add('emergencyphone',     TextType::class, array('required' => false))
            ->add('birthday',           BirthdayType::class, array('required' => false))
            ->add('newsletter',         CheckboxType::class, array('required' => false))
            ->add('save',               SubmitType::class)
        ;

        if($this->authorization->isGranted('ROLE_WEBMASTER'))
            {
                $builder->add('userRoles', EntityType::class, array(
                    'class'     => 'OSELUserBundle:Roles',
                    'choice_label'  => 'role',
                    'multiple'  => true,
                    'required' => false));
            }
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
