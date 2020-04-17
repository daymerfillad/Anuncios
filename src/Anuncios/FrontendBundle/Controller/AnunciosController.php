<?php

namespace Anuncios\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Anuncios\FrontendBundle\Entity\Anuncio;
use Anuncios\FrontendBundle\Form\AnuncioType;
use Anuncios\FrontendBundle\Entity\Imagen;
use Anuncios\FrontendBundle\Util\Util;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AnunciosController extends Controller
{    
    public function anunciosUsuarioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $anuncios = $em->getRepository('FrontendBundle:Anuncio')->getAnunciosByUser($this->getUser());          
        return $this->render('FrontendBundle:Anuncios:anunciosUsuario.html.twig', array(
            'anuncios' => $anuncios,
        ));
    }
    
    public function anuncioDetallesAction($slugAnuncio)
    {
        $em = $this->getDoctrine()->getManager();
        $anuncio = $em->getRepository('FrontendBundle:Anuncio')->getAnuncioBySlug($slugAnuncio);        
        return $this->render('FrontendBundle:Anuncios:anuncioDetalles.html.twig', array(
            'anuncio' => $anuncio,
        ));
    }

    public function autenticarseAction()
    {
        $peticion = $this->getRequest();
        $sesion = $peticion->getSession();
        $error = $peticion->attributes->get(
            SecurityContext::AUTHENTICATION_ERROR,
            $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
        );
        return $this->render('FrontendBundle:Otros:autenticarse.html.twig', array(
            'error' => $error,
        ));
    }
    public function anuncioEliminarAction($slugAnuncio)
    {
        $em = $this->getDoctrine()->getManager();
        $anuncio = $em->getRepository('FrontendBundle:Anuncio')->findOneBySlug($slugAnuncio);
        
        if(!$anuncio)
            throw $this->createNotFoundException('No existe el anuncio: '.$slugAnuncio);
        
        if($this->get('security.context')->isGranted('DELETE', $anuncio)===false)
            throw new AccessDeniedException('Esta intentando eliminar un anuncio del cual usted no es propietario.');
        
        $imagenes = $anuncio->getImagenes();
        $directorio = $this->container->getParameter('anuncios.directorio.web').'/bundles/frontend/img/anuncios/';
        
        foreach ($imagenes as $img) 
            unlink($directorio.$img->getImagen());        
                        
        $idObjeto = ObjectIdentity::fromDomainObject($anuncio);
        $this->get('security.acl.provider')->deleteAcl($idObjeto);        
        
        $em->remove($anuncio);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('info',
            'Se ha adicionado correctamente el Anuncio.'
        );

       return $this->redirect($this->generateUrl('anuncios_usuario'));
    }
    
    public function anuncioEditarAction($slugAnuncio)
    {
        $em = $this->getDoctrine()->getManager();
        $anuncio = $em->getRepository('FrontendBundle:Anuncio')->findOneBySlug($slugAnuncio);
        
        if(!$anuncio)
            throw $this->createNotFoundException('No existe el anuncio: '.$slugAnuncio);
        
        if($this->get('security.context')->isGranted('EDIT', $anuncio)===false)
            throw new AccessDeniedException('Esta intentando editar un anuncio del cual usted no es propietario.');


            $imagenesViejas = clone $anuncio->getImagenes();
        foreach ($imagenesViejas as $img)        
            $anuncio->removeImagene($img);
        
        $imagen1 = new Imagen();
        $imagen2 = new Imagen();
        $imagen3 = new Imagen();
        
        $anuncio->addImagene($imagen1);
        $anuncio->addImagene($imagen2);
        $anuncio->addImagene($imagen3);
        $formulario  = $this->createForm(new AnuncioType(), $anuncio);
        $peticion = $this->getRequest();
        if($peticion->getMethod() == 'POST')
        {
           $nuevas = false;
           $formulario->bind($peticion);
           if($imagen1->getImagen() == null && $imagen2->getImagen() == null && $imagen3->getImagen() == null)
           {
               $anuncio->removeImagene($imagen1);
               $anuncio->removeImagene($imagen2);
               $anuncio->removeImagene($imagen3);
               foreach ($imagenesViejas as $img)        
                   $anuncio->addImagene($img);
           }
           else
           {           
               $vimagen1 = $imagen1->validar('500', 1);
               $vimagen2 = $imagen2->validar('500', 2);
               $vimagen3 = $imagen3->validar('500', 3);

               if($vimagen1)
                   $formulario->get('imagenes')->addError(new \Symfony\Component\Form\FormError($vimagen1));
               if($vimagen2)
                   $formulario->get('imagenes')->addError(new \Symfony\Component\Form\FormError($vimagen2));
               if($vimagen3)
                   $formulario->get('imagenes')->addError(new \Symfony\Component\Form\FormError($vimagen3));
               $nuevas = true;
               
           }
           if($formulario->isValid())
           {                  
               $em = $this->getDoctrine()->getManager();
               $anuncio->setFecha(new \DateTime());              
               $anuncio->setSlug(Util::getSlug($anuncio->getAsunto()).uniqid('-'));
               $directorio = $this->container->getParameter('anuncios.directorio.web').'/bundles/frontend/img/anuncios/';
               
               if($nuevas)
               {                   
                   foreach ($imagenesViejas as $img) 
                   {
                       unlink ($directorio.$img->getImagen());                  
                       $em->remove($img);
                   }    
                   if(!$imagen1->subirImagen($directorio))
                        $anuncio->removeImagene($imagen1);               
                   if(!$imagen2->subirImagen($directorio))
                       $anuncio->removeImagene($imagen2);
                   if(!$imagen3->subirImagen($directorio))
                       $anuncio->removeImagene($imagen3);                    
               }
               
               $em->persist($anuncio);
               $em->flush();
                
               $this->get('session')->getFlashBag()->add('info',
                   'Se ha adicionado correctamente el Anuncio.'
               );

               return $this->redirect($this->generateUrl('anuncios_usuario'));
           } 
        }
        return $this->render('FrontendBundle:Anuncios:anuncioFormulario.html.twig', array(
            'accion' => 'Editar',
            'formulario' => $formulario->createView(),
            'slug' => $slugAnuncio,
            'imagenesViejas' => $imagenesViejas
        ));
    }
    
    public function anuncioNuevoAction()
    {           
        $anuncio = new Anuncio();
        $imagen1 = new Imagen();
        $imagen2 = new Imagen();
        $imagen3 = new Imagen();
        $anuncio->addImagene($imagen1);
        $anuncio->addImagene($imagen2);
        $anuncio->addImagene($imagen3);
        $formulario = $this->createForm(new AnuncioType(), $anuncio);
        
        $peticion = $this->getRequest();
        if($peticion->getMethod()== 'POST')
        {
           $formulario->bind($peticion);
                     
           $vimagen1 = $imagen1->validar('500', 1);
           $vimagen2 = $imagen2->validar('500', 2);
           $vimagen3 = $imagen3->validar('500', 3);
           
           if($vimagen1)
               $formulario->get('imagenes')->addError(new \Symfony\Component\Form\FormError($vimagen1));
           if($vimagen2)
               $formulario->get('imagenes')->addError(new \Symfony\Component\Form\FormError($vimagen2));
           if($vimagen3)
               $formulario->get('imagenes')->addError(new \Symfony\Component\Form\FormError($vimagen3));
           
           if($formulario->isValid())
           {   
               $em = $this->getDoctrine()->getManager();
               $usuario = $this->getUser();
               $usuario = $em->getRepository('FrontendBundle:Usuario')->findOneById($usuario->getId());
                              
               $anuncio->setFecha(new \DateTime());
               $anuncio->setUsuario($usuario);
               $anuncio->setSlug(Util::getSlug($anuncio->getAsunto()).uniqid('-'));
               $directorio = $this->container->getParameter('anuncios.directorio.web').'/bundles/frontend/img/anuncios/';
               
               if(!$imagen1->subirImagen($directorio))
                    $anuncio->removeImagene($imagen1);               
               if(!$imagen2->subirImagen($directorio))
                   $anuncio->removeImagene($imagen2);
               if(!$imagen3->subirImagen($directorio))
                   $anuncio->removeImagene($imagen3); 
               
               $em->persist($anuncio);
               $em->flush();
                              
               $idObjeto = ObjectIdentity::fromDomainObject($anuncio);
               $idUsuario = UserSecurityIdentity::fromAccount($usuario);
               
               $acl = $this->get('security.acl.provider')->createAcl($idObjeto);
               $acl->insertObjectAce($idUsuario, MaskBuilder::MASK_OPERATOR);
               $this->get('security.acl.provider')->updateAcl($acl);
               
               $this->get('session')->getFlashBag()->add('info',
                   'Se ha adicionado correctamente el Anuncio.'
               );

               return $this->redirect($this->generateUrl('homepage'));
           }           
        }
        return $this->render('FrontendBundle:Anuncios:anuncioFormulario.html.twig', array(
            'formulario' => $formulario->createView(),
            'accion' => 'Nuevo',
        ));
    }
    
    public function anunciosAction($categoria)
    {
        $em = $this->getDoctrine()->getManager();
        $anuncios = $em->getRepository('FrontendBundle:Anuncio')->getAnunciosBySlug($categoria);        
        return $this->render('FrontendBundle:Anuncios:anuncios.html.twig', array(
            'anuncios' => $anuncios,  
            'categoria' => $categoria,
        ));
    }
    
    public function inicioAction()
    {   
        $em = $this->getDoctrine()->getManager();
        $top = $em->getRepository('FrontendBundle:Categoria')->getTopCategorias();
        $categoriaAnuncios = array();
        foreach ($top as $value)
            $categoriaAnuncios[] = $em->getRepository('FrontendBundle:Anuncio')->getAnunciosBySlug($value['slug'], 6);        
        return $this->render('FrontendBundle:Anuncios:inicio.html.twig', array(
            'categoriaAnuncios' => $categoriaAnuncios,
        ));
    }
    
    public function menuCategoriaAction()
    {        
        $em = $this->getDoctrine()->getManager();
        $categorias = $em->getRepository('FrontendBundle:Categoria')->getCategorias();
        return $this->render('FrontendBundle:Anuncios:menuCategoria.html.twig', array(
            'categorias' => $categorias,
        ));
    }
    
    public function formBuscarConCategoriaAction()
    {        
        $em = $this->getDoctrine()->getManager();
        $categorias = $em->getRepository('FrontendBundle:Categoria')->getCategorias();
        return $this->render('FrontendBundle:Buscar:buscarConCategoria.html.twig', array(
            'categorias' => $categorias,
        ));
    }
    
    public function ultimosAnunciosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $anuncios = $em->getRepository('FrontendBundle:Anuncio')->getUltimosAnuncios();        
        return $this->render('FrontendBundle:Anuncios:ultimos.html.twig', array(
            'anuncios' => $anuncios,
        ));
    }
    
    public function buscarAction()
    {
        $em = $this->getDoctrine()->getManager();     
        $valor = $this->getRequest()->get('buscar');
        $categoria = $this->getRequest()->get('categoria');
        $anunciosA = $em->getRepository('FrontendBundle:Anuncio')->buscarAnunciosByCategoriaAsunto($valor, $categoria);        
        $idArray = array(0);
        foreach ($anunciosA as $anuncio)
            $idArray[] = $anuncio['id'];
        $anunciosD = $em->getRepository('FrontendBundle:Anuncio')->buscarAnunciosByCategoriaDescripcion($valor, $categoria, $idArray);                
        $anuncios = array_merge($anunciosA, $anunciosD);
        return $this->render('FrontendBundle:Buscar:anunciosBuscar.html.twig', array(
            'anuncios' => $anuncios,
        ));
    }
    
    public function buscarEnCategoriaAction()
    {
        $em = $this->getDoctrine()->getManager();     
        $valor = $this->getRequest()->get('buscar');
        $categoria = $this->getRequest()->get('categoria');
        $anunciosA = $em->getRepository('FrontendBundle:Anuncio')->buscarAnunciosByCategoriaAsunto($valor, $categoria);        
        $idArray = array(0);
        foreach ($anunciosA as $anuncio)
            $idArray[] = $anuncio['id'];
        $anunciosD = $em->getRepository('FrontendBundle:Anuncio')->buscarAnunciosByCategoriaDescripcion($valor, $categoria, $idArray);                
        $anuncios = array_merge($anunciosA, $anunciosD);
        return $this->render('FrontendBundle:Anuncios:anuncios.html.twig', array(
            'anuncios' => $anuncios,  
            'categoria' => $categoria,
        ));
    }
}
