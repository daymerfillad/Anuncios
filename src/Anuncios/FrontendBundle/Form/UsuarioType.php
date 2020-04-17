<?php

namespace Anuncios\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'label'  => 'Usuario:'
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'La contraseña debe coincidir',
                'first_options' => array('label' => 'Contraseña'),
                'second_options' => array('label' => 'Repetir contraseña'),
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Anuncios\FrontendBundle\Entity\Usuario'
        ));
    }

    public function getName()
    {
        return 'anuncios_frontendbundle_usuariotype';
    }
}
