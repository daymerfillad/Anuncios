<?php

namespace Anuncios\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Anuncios\FrontendBundle\Entity\Usuario;
use Anuncios\FrontendBundle\Form\UsuarioType;
use Anuncios\FrontendBundle\Form\UsuarioEditarType;
use Anuncios\FrontendBundle\Entity\Categoria;
use Anuncios\FrontendBundle\Form\CategoriaType;
use Anuncios\FrontendBundle\Util\Util;

class AdminController extends Controller
{    
    public function usuarioEliminarAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('FrontendBundle:Usuario')->findOneById($id);
        
        if(!$usuario)
            throw $this->createNotFoundException('No existe el usuario: '.$id);
        
        $anuncios = $em->getRepository('FrontendBundle:Anuncio')->getAnunciosByUser($usuario);
        $directorio = $this->container->getParameter('anuncios.directorio.web').'/bundles/frontend/img/anuncios/';
        foreach ($anuncios as $anuncio)
        {
            foreach ($anuncio['imagenes'] as $imagen)
                unlink($directorio.$imagen['imagen']);
        }
        $em->remove($usuario);
        $em->flush();
                
        $this->get('session')->getFlashBag()->add('info',
            'Se ha eliminado el usuario y sus respectivos anuncios correctamente.'
        );

       return $this->redirect($this->generateUrl('admin_usuario'));
    }
    
    public function usuarioEditarAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('FrontendBundle:Usuario')->findOneById($id);
        
        if(!$usuario)
            throw $this->createNotFoundException('No existe el usuario: '.$id);
        
        $formulario = $this->createForm(new UsuarioEditarType(), $usuario);
        $peticion = $this->getRequest();
        if($peticion->getMethod() == 'POST')
        {
            $passwordOld = $usuario->getPassword();
            $formulario->bind($peticion);            
            if($formulario->isValid())
            {
                if($usuario->getPassword() == null)
                    $usuario->setPassword($passwordOld);
                else
                {
                    $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
                    $passwordCodificado = $encoder->encodePassword(
                        $usuario->getPassword(),
                        $usuario->getSalt()
                    );
                    $usuario->setPassword($passwordCodificado);                     
                }
                $em->persist($usuario);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info',
                    'Se ha actualizado el usuario correctamente.'
                );

                return $this->redirect($this->generateUrl('admin_usuario'));
            }
        }
        return $this->render('FrontendBundle:Admin/usuarios:formulario.html.twig', array(
            'formulario' => $formulario->createView(),
            'idUsuario' => $id,
            'accion' => 'Editar',
        ));
        
    }
    
    public function usuarioActivarAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('FrontendBundle:Usuario')->findOneById($id);
        
        if(!$usuario)
            throw $this->createNotFoundException('No existe el usuario: '.$id);
        
        $usuario->setActivado(true);
        $em->persist($usuario);
        $em->flush();
                
        $this->get('session')->getFlashBag()->add('info',
            'Se ha activado el usuario correctamente.'
        );

       return $this->redirect($this->generateUrl('admin_usuario'));
    }
    
    public function usuarioDesactivarAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('FrontendBundle:Usuario')->findOneById($id);
        
        if(!$usuario)
            throw $this->createNotFoundException('No existe el usuario: '.$id);
        
        $usuario->setActivado(false);
        $em->persist($usuario);
        $em->flush();
                
        $this->get('session')->getFlashBag()->add('info',
            'Se ha desactivado el usuario correctamente.'
        );

       return $this->redirect($this->generateUrl('admin_usuario'));
    }
    
    public function usuariosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('FrontendBundle:Usuario')->getUsuarios();        
        return $this->render('FrontendBundle:Admin/usuarios:usuarios.html.twig', array(
            'usuarios' => $usuarios,
        ));
    }    
    
    public function categoriasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categorias = $em->getRepository('FrontendBundle:Categoria')->getCategorias();        
        return $this->render('FrontendBundle:Admin/categorias:categorias.html.twig', array(
            'categorias' => $categorias,
        ));
    }
    
    public function categoriaNuevaAction()
    {        
        $categoria = new Categoria();
        $formulario = $this->createForm(new CategoriaType(), $categoria);
        
        $peticion = $this->getRequest();
        if($peticion->getMethod()=='POST')
        {
            $formulario->bind($peticion);
            if($formulario->isValid())
            {
               $em = $this->getDoctrine()->getManager();
               $categoria->setSlug(Util::getSlug($categoria->getCategoria()));
                
               $em->persist($categoria);
               $em->flush();
                
               $this->get('session')->getFlashBag()->add('info',
                   'Se ha adicionado correctamente la categoria <strong>'.$categoria->getCategoria().'</strong>.'
               );

               return $this->redirect($this->generateUrl('admin_categoria'));
            }
        }
        
        return $this->render('FrontendBundle:Admin/categorias:formulario.html.twig', array(
            'formulario' => $formulario->createView(),
            'accion' => 'Nuevo',
        ));
    }
    
    public function categoriaEditarAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $categoria = $em->getRepository('FrontendBundle:Categoria')->findOneBySlug($slug);
        
        if(!$categoria)
            throw $this->createNotFoundException('No existe la categoria: '.$slug);
                
        $formulario  = $this->createForm(new CategoriaType(), $categoria);
        $peticion = $this->getRequest();
        if($peticion->getMethod() == 'POST')
        {
            $formulario->bind($peticion);
            if($formulario->isValid())
            {
               $em = $this->getDoctrine()->getManager();
               $categoria->setSlug(Util::getSlug($categoria->getCategoria()));
                
               $em->persist($categoria);
               $em->flush();
                
               $this->get('session')->getFlashBag()->add('info',
                   'Se ha modificado correctamente la categoria <strong>'.$categoria->getCategoria().'</strong>.'
               );

               return $this->redirect($this->generateUrl('admin_categoria'));
            }
        }
        return $this->render('FrontendBundle:Admin/categorias:formulario.html.twig', array(
            'formulario' => $formulario->createView(),
            'accion' => 'Editar',
            'slug' => $slug,
        ));
    }
    
    public function categoriaEliminarAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $categoria = $em->getRepository('FrontendBundle:Categoria')->findOneBySlug($slug);
        
        if(!$categoria)
            throw $this->createNotFoundException('No existe la categoria: '.$slug);
        
        $directorio = $this->container->getParameter('anuncios.directorio.web').'/bundles/frontend/img/anuncios/';        
        foreach ($categoria->getAnuncios() as $anuncio)
        {
            foreach ($anuncio->getImagenes() as $imagen)                
                unlink($directorio.$imagen->getImagen());
        }        
        $em->remove($categoria);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info',
            'Se ha eliminado la categoria y todos sus respectivos anuncios correctamente'
        );

        return $this->redirect($this->generateUrl('admin_categoria'));
            
      
    }
    
    public function usuarioNuevoAction()
    {
        $usuario = new Usuario();
        $formulario = $this->createForm(new UsuarioType(), $usuario);
        
        $peticion = $this->getRequest();
        if($peticion->getMethod()=='POST')
        {
            $formulario->bind($peticion);
            if($formulario->isValid())
            {
               $em = $this->getDoctrine()->getManager();
               $usuario->setActivado(true);
               $usuario->setRole('ROLE_USER');
               $usuario->setSalt(md5(time()));
             
               $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
               $passwordCodificado = $encoder->encodePassword(
                   $usuario->getPassword(),
                   $usuario->getSalt()
               );
               $usuario->setPassword($passwordCodificado);
                
               $em->persist($usuario);
               $em->flush();
                
               $this->get('session')->getFlashBag()->add('info',
                   'Se ha adicionado correctamente el usuario <strong>'.$usuario->getUsername().'</strong>.'
               );

               return $this->redirect($this->generateUrl('admin_usuario'));
            }
        }
        
        return $this->render('FrontendBundle:Admin/usuarios:formulario.html.twig', array(
            'formulario' => $formulario->createView(),
            'accion' => 'Nuevo',
        ));
    } 
}
