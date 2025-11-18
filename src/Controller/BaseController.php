<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categoria;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    public function anadir_producto(
        int $id,
        EntityManagerInterface $em,
        Request $request
    ): RedirectResponse {
        $producto = $em->getRepository(Producto::class)->find($id);

        /* if (!$producto) {
            throw $this->createNotFoundException('Producto no encontrado');
        }

        // Obtenemos la cesta actual desde la sesión
        $session = $request->getSession();
        $cesta = $session->get('cesta', []);

        // Añadimos el producto o incrementamos cantidad si ya existe
        if (isset($cesta[$id])) {
            $cesta[$id]['cantidad']++;
        } else {
            $cesta[$id] = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'precio' => $producto->getPrecio(),
                'cantidad' => 1,
            ];
        }

        // Guardamos de nuevo en sesión
        $session->set('cesta', $cesta);

        // Redirigimos a la ruta 'cesta'
        return $this->redirectToRoute('cesta');
        */
    }
}
