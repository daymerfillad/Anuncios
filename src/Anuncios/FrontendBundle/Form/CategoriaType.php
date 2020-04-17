<?php

namespace Anuncios\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoria','text', array(
                'label' => 'Nombre:'
            ))
            ->add('prioridad', 'text', array(
                'label' => 'Prioridad:',
                'pattern' => '^[0-9]{1,3}$',
                'max_length' => 3
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Anuncios\FrontendBundle\Entity\Categoria'
        ));
    }

    public function getName()
    {
        return 'anuncios_frontendbundle_categoriatype';
    }
}
