<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categoria;
use App\Entity\Producto;
use App\Service\CestaCompra;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request ;

// Clase controladora para ROLE_USER
#[IsGranted('ROLE_USER')]
final class BaseController extends AbstractController
{
    #[Route('/categorias', name: 'categorias')]
    public function mostrar_categorias(EntityManagerInterface $em): Response
    {
        $categorias = $em->getRepository(Categoria::class)->findAll();

        return $this->render('categorias/mostrar_categorias.html.twig', [
            'categorias' => $categorias,
        ]);
    }

    #[Route('/productos/{categoria}', name: 'productos', defaults: ['categoria' => null])]
    public function mostrar_productos(EntityManagerInterface $em, ?int $categoria = null): Response
    {        
        // Obtenemos el objeto categoria correspondiente al id pasado como parametro
        $categoriaObj = $em->getRepository(Categoria::class)->find($categoria);

        // Obtenemos los productos de esa categoria
        $productos = $categoriaObj->getProductos();

        return $this->render('productos/mostrar_productos.html.twig', [
            'productos' => $productos,
        ]);
    }
    
    #[Route('/anadir', name: 'anadir')]
    public function anadir_productos( EntityManagerInterface $em, Request $request, CestaCompra $cesta){
        // recogemos los datos de la entrada
        $productos_ids = $request->request->all("productos_ids");
        $unidades = $request->request->all("unidades");
        
        // Obtenemos un array de objetos de producto a partir de sus ids
        $productos = $em->getRepository(Producto::class)->findBy(['id' => $productos_ids]);
        
        // Llamamos a carga_productos para añadir a la cesta los productos junto con sus unidades
        $cesta->cargar_productos($productos, $unidades);
        
        // Tomamos el primer producto para obtener la categoría
        $primerProducto = array_values($productos)[0];
        
        //Obtenemos el ID de la categoria del primer producto
        $categoriaId = $primerProducto->getCategoria()->getId();

        // Redirigimos a la misma página de productos de esa categoría
        return $this->redirectToRoute('productos', [
            'categoria' => $categoriaId
        ]);
    }
    
    #[Route('/cesta', name: 'cesta')]
    public function mostrar_cesta(CestaCompra $cesta){
        return $this->render('cesta/mostrar_cesta.html.twig', [
            'productos' => $cesta->get_productos(),
            'unidades' => $cesta->get_unidades()
        ]);
    }
    
    #[Route('/eliminar', name: 'eliminar')]
    public function eliminar(Request $request, CestaCompra $cesta){
        $producto_id = $request->request->get("producto_id");
        $unidades = $request->request->get("unidades");
        
        $cesta->eliminar_producto($producto_id, $unidades);
        
        return $this->redirectToRoute('cesta');
    }
}
