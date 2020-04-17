<?php

namespace Anuncios\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Categoria
 *
 * @ORM\Table(name="categoria")
 * @ORM\Entity(repositoryClass="Anuncios\FrontendBundle\Entity\CategoriaRepository")
 * @DoctrineAssert\UniqueEntity("categoria")
 * @DoctrineAssert\UniqueEntity("prioridad")
 */
class Categoria
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="categoria", type="string", length=35, nullable=false, unique=true)     
     */
    private $categoria;
    
    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;
    
    /**
     * @var string
     *
     * @ORM\Column(name="prioridad", type="integer", nullable=false, unique=true)
     * @Assert\Regex("/^[0-9]{1,3}$/")
     * @Assert\Length(max=3)
     */
    private $prioridad;

    /**
    * @ORM\OneToMany(targetEntity="Anuncio", mappedBy="categoria")      
    */    
    private $anuncios;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categoria
     *
     * @param string $categoria
     * @return Categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return string 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Categoria
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    public function __toString() {
        return $this->categoria;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->anuncios = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add anuncios
     *
     * @param \Anuncios\FrontendBundle\Entity\Anuncio $anuncios
     * @return Categoria
     */
    public function addAnuncio(\Anuncios\FrontendBundle\Entity\Anuncio $anuncios)
    {
        $this->anuncios[] = $anuncios;
    
        return $this;
    }

    /**
     * Remove anuncios
     *
     * @param \Anuncios\FrontendBundle\Entity\Anuncio $anuncios
     */
    public function removeAnuncio(\Anuncios\FrontendBundle\Entity\Anuncio $anuncios)
    {
        $this->anuncios->removeElement($anuncios);
    }

    /**
     * Get anuncios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnuncios()
    {
        return $this->anuncios;
    }

    /**
     * Set prioridad
     *
     * @param integer $prioridad
     * @return Categoria
     */
    public function setPrioridad($prioridad)
    {
        $this->prioridad = $prioridad;
    
        return $this;
    }

    /**
     * Get prioridad
     *
     * @return integer 
     */
    public function getPrioridad()
    {
        return $this->prioridad;
    }
}