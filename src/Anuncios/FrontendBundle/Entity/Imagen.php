<?php

namespace Anuncios\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Imagen
 *
 * @ORM\Table(name="imagen")
 * @ORM\Entity
 */
class Imagen
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
     * @ORM\Column(name="imagen", type="string", length=100, nullable=false)
     * @Assert\Image(maxSize = "500k")
     */
    protected $imagen;

    /**
     * @var \Anuncio
     *
     * @ORM\ManyToOne(targetEntity="Anuncio", inversedBy="imagenes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="anuncio_id", referencedColumnName="id", onDelete="Cascade")
     * })
     */
    protected $anuncio;



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
     * Set imagen
     *
     * @param string $imagen
     * @return Imagen
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    
        return $this;
    }

    /**
     * Get imagen
     *
     * @return string 
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set anuncio
     *
     * @param \Anuncios\FrontendBundle\Entity\Anuncio $anuncio
     * @return Imagen
     */
    public function setAnuncio(\Anuncios\FrontendBundle\Entity\Anuncio $anuncio = null)
    {
        $this->anuncio = $anuncio;
    
        return $this;
    }

    /**
     * Get anuncio
     *
     * @return \Anuncios\FrontendBundle\Entity\Anuncio 
     */
    public function getAnuncio()
    {
        return $this->anuncio;
    }
    
    public function subirImagen($directorio)
    {
        if($this->imagen === null)
            return false;
        $nombreImagen = uniqid('anuncio-').'.'.$this->imagen->guessExtension();
        $this->imagen->move($directorio, $nombreImagen);
        $this->setImagen($nombreImagen);
        return true;
    }
    
    public function validar($size, $numeroImagen)
    {
        if($this->imagen !== null)
        {
            $tipo = explode('/', $this->imagen->getClientMimeType());
            if($tipo[0]!='image')
                return 'La imagen #'.$numeroImagen.' debe ser una imagen valida';
            if(($this->imagen->getClientSize()/1024)>$size)
                return 'La imagen #'.$numeroImagen.' no debe ser mayor de '.$size.'KB';
        }
        return false;
    }
}