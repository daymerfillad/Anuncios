<?php

namespace Anuncios\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Anuncio
 *
 * @ORM\Table(name="anuncio")
 * @ORM\Entity(repositoryClass="Anuncios\FrontendBundle\Entity\AnuncioRepository")
 */
class Anuncio
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
     * @ORM\Column(name="asunto", type="string", length=70, nullable=false)
     * @Assert\Length(max=70)
     * @Assert\NotBlank()
     */
    private $asunto;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="float", nullable=false)
     * @Assert\Length(max=10)
     * @Assert\Regex("/^[0-9]*[.,]{0,1}[0-9]{1,2}$/")
     * @Assert\NotBlank()
     */
    private $precio;

    /**
     * @var string
     *
     * @ORM\Column(name="moneda", type="string", length=3, nullable=false)
     */
    private $moneda;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message = "Escribe tu nombre"))
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=50, nullable=false) 
     * @Assert\NotBlank()    
     */
    private $telefono;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="Cascade", nullable=false)
     * })
     */
    private $usuario;

    /**
     * @var \Categoria
     *
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="anuncios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoria_id", referencedColumnName="id", onDelete="Cascade", nullable=false)
     * })
     */
    private $categoria;

    /**
    * @ORM\OneToMany(targetEntity="Imagen", mappedBy="anuncio", cascade={"persist"})      
    */    
    private $imagenes;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)     
     */
    private $slug;
    
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
     * Set asunto
     *
     * @param string $asunto
     * @return Anuncio
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;
    
        return $this;
    }

    /**
     * Get asunto
     *
     * @return string 
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set precio
     *
     * @param float $precio
     * @return Anuncio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    
        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set moneda
     *
     * @param string $moneda
     * @return Anuncio
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;
    
        return $this;
    }

    /**
     * Get moneda
     *
     * @return string 
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Anuncio
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Anuncio
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Anuncio
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Anuncio
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set usuario
     *
     * @param \Anuncios\FrontendBundle\Entity\Usuario $usuario
     * @return Anuncio
     */
    public function setUsuario(\Anuncios\FrontendBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Anuncios\FrontendBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set categoria
     *
     * @param \Anuncios\FrontendBundle\Entity\Categoria $categoria
     * @return Anuncio
     */
    public function setCategoria(\Anuncios\FrontendBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return \Anuncios\FrontendBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->imagenes = new \Doctrine\Common\Collections\ArrayCollection();
    }    

    /**
     * Add imagenes
     *
     * @param \Anuncios\FrontendBundle\Entity\Imagen $imagenes
     * @return Anuncio
     */
    public function addImagene(\Anuncios\FrontendBundle\Entity\Imagen $imagenes)
    {
        $imagenes->setAnuncio($this);
        $this->imagenes[] = $imagenes;
    
        return $this;
    }

    /**
     * Remove imagenes
     *
     * @param \Anuncios\FrontendBundle\Entity\Imagen $imagenes
     */
    public function removeImagene(\Anuncios\FrontendBundle\Entity\Imagen $imagenes)
    {
        $this->imagenes->removeElement($imagenes);
    }

    /**
     * Get imagenes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImagenes()
    {
        return $this->imagenes;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Anuncio
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
}