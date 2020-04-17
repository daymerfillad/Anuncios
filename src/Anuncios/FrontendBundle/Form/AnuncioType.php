<?php

namespace Anuncios\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AnuncioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('asunto', 'text', array(
                'label' => 'Asunto:',
                'max_length' => 70
            ))
            ->add('precio', 'text', array(
                'label' => 'Precio:',
                'pattern' => '^[0-9]*[.,]{0,1}[0-9]{1,2}$',
                'max_length' => 10
            ))
            ->add('moneda','choice', array(
                'choices' => array(
                    'CUC' => 'CUC',
                    'MN' => 'MN'
                ),
                'required' => true,
                'label' => 'Moneda:'
            ))
            ->add('descripcion', 'textarea', array(
                'label' => 'Descripción:',
                'required' => false
            ))
            ->add('nombre', 'text', array(
                'label' => 'Nombre:'
            ))
            ->add('telefono', 'text', array(
                'label' => 'Teléfono(s):'
            ))
            ->add('categoria', 'entity', array(
                'label' => 'Categoria:',
                'class' => 'FrontendBundle:Categoria',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.prioridad', 'ASC');
                },
            ))
            ->add('imagenes', 'collection', array(
                'type' => new ImagenType(),                
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Anuncios\FrontendBundle\Entity\Anuncio'
        ));
    }

    public function getName()
    {
        return 'anuncios_frontendbundle_anunciotype';
    }
}
