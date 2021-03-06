<?php

namespace Anuncios\FrontendBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AnuncioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AnuncioRepository extends EntityRepository
{
    public function getAnunciosByUser($user)
    {
        $em = $this->getEntityManager();        
        $consulta = $em->createQuery('
            SELECT a, c, i
            FROM FrontendBundle:Anuncio a JOIN a.categoria c LEFT JOIN a.imagenes i
            WHERE a.usuario = :user
        ');
        $consulta->setParameter('user', $user);
        return $consulta->getArrayResult();
    }
    
    public function getAnunciosBySlug($slug, $cant=false)
    {
        $em = $this->getEntityManager();        
        $consulta = $em->createQuery('
            SELECT a, c, i
            FROM FrontendBundle:Anuncio a JOIN a.categoria c LEFT JOIN a.imagenes i
            WHERE c.slug = :slug
            ORDER BY a.fecha DESC
        ');
        $consulta->setParameter('slug', $slug);
        if($cant)
            $consulta->setMaxResults($cant);
        return $consulta->getArrayResult();
    }
    
    public function getAnuncioBySlug($slug)
    {
        $em = $this->getEntityManager();        
        $consulta = $em->createQuery('
            SELECT a, i
            FROM FrontendBundle:Anuncio a LEFT JOIN a.imagenes i
            WHERE a.slug = :slug            
        ');
        $consulta->setParameter('slug', $slug);
        return $consulta->getSingleResult();
    }
    
    public function getUltimosAnuncios()
    {
        $em = $this->getEntityManager();        
        $consulta = $em->createQuery('
            SELECT a, i, c
            FROM FrontendBundle:Anuncio a LEFT JOIN a.imagenes i JOIN a.categoria c
            ORDER BY a.fecha DESC
        ');
        return $consulta->getArrayResult();
    }
    
    public function buscarAnunciosByCategoriaAsunto($valor, $categoria)
    {
        $em = $this->getEntityManager();        
        $query = 'SELECT a, i, c
            FROM FrontendBundle:Anuncio a LEFT JOIN a.imagenes i JOIN a.categoria c
            WHERE a.asunto LIKE :valor';
        if($categoria!='todas')
            $query.=' AND c.slug = :categoria';
        $query.=' ORDER BY a.fecha DESC';
        $consulta = $em->createQuery($query);
        $consulta->setParameter('valor', '%'.$valor.'%');
        if($categoria!='todas')
            $consulta->setParameter('categoria', $categoria);
        return $consulta->getArrayResult();
    }
    
    public function buscarAnunciosByCategoriaDescripcion($valor, $categoria, $arrayId)
    {
        $em = $this->getEntityManager();        
        $query = 'SELECT a, i, c
            FROM FrontendBundle:Anuncio a LEFT JOIN a.imagenes i JOIN a.categoria c
            WHERE a.descripcion LIKE :valor AND a.id NOT IN (:arrayId)';
        if($categoria!='todas')
            $query.=' AND c.slug = :categoria';
        $query.=' ORDER BY a.fecha DESC';
        $consulta = $em->createQuery($query);
        $consulta->setParameter('valor', '%'.$valor.'%');
        $consulta->setParameter('arrayId', $arrayId);
        if($categoria!='todas')
            $consulta->setParameter('categoria', $categoria);
        return $consulta->getArrayResult();
    }    
}
