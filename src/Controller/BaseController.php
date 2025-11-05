<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categoria;
use App\Entity\Producto;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// Clase controladora para ROLE_USER
#[IsGranted('ROLE_USER')]
final class BaseController extends AbstractController
{
    #[Route('/categorias', name: 'app_categorias')]
    public function mostrar_categorias(EntityManagerInterface $em): Response
    {
        $categorias = $em->getRepository(Categoria::class)->findAll();

        return $this->render('categorias/mostrar_categorias.html.twig', [
            'categorias' => $categorias,
        ]);
    }

    #[Route('/productos/{categoria}', name: 'app_productos', defaults: ['categoria' => null])]
    public function mostrar_productos(EntityManagerInterface $em, ?int $categoria = null): Response
    {        // Obtenemos el objeto categoria correspondiente al id pasado como parametro
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

    #[Route('/producto/{id}', name: 'app_producto')]
    public function mostrar_producto(EntityManagerInterface $em, int $id): Response
    {
        $producto = $em->getRepository(Producto::class)->find($id);

        if (!$producto) {
            throw $this->createNotFoundException('Producto no encontrado');
        }

        return $this->render('producto/mostrar_producto.html.twig', [
            'producto' => $producto,
        ]);
    }
}
