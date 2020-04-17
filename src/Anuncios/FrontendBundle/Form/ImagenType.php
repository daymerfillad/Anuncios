<?php

namespace Anuncios\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImagenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imagen', 'file', array(
                'required' => false,
                'by_reference' => false,
                'data_class' => null,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Anuncios\FrontendBundle\Entity\Imagen'
        ));
    }

    public function getName()
    {
        return 'anuncios_frontendbundle_imagentype';
    }
}
