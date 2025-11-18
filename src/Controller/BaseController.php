<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categoria;
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
        if (!$categoriaObj) {
            throw $this->createNotFoundException('Categoría no encontrada');
        }
        // Obtenemos los productos de esa categoria
        $productos = $categoriaObj->getProductos();

        return $this->render('productos/mostrar_productos.html.twig', [
            'productos' => $productos,
        ]);
    }
    
    #[Route('/anadir', name: 'anadir')]
    public function anadir_producto( EntityManagerInterface $em, Request $request, CestaCompra $cesta){
        // recogemos los datos de la entrada
        $productos_ids = $request->request->get("productos_ids");
        $unidades = $request->request->get("unidades");
        $productos = $em->getRepository(Producto::class)->findProductosByIds($productos_ids);
        $cesta->cargar_articulos($productos, $unidades);
        
        return $this->redirectToRoute('mostrar_cesta');
    }
}
