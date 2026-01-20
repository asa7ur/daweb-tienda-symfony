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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        $categoriaObj = $em->getRepository(Categoria::class)->find($categoria);
        $productos = $categoriaObj->getProductos();
        return $this->render('productos/mostrar_productos.html.twig', [
            'productos' => $productos,
        ]);
    }

    #[Route('/anadir', name: 'anadir')]
    public function anadir_productos(EntityManagerInterface $em, Request $request, CestaCompra $cesta): RedirectResponse
    {
        $productos_ids = $request->request->all("productos_ids");
        $unidades = $request->request->all("unidades");
        $productos = $em->getRepository(Producto::class)->findBy(['id' => $productos_ids]);
        $cesta->cargar_productos($productos, $unidades);

        $primerProducto = array_values($productos)[0];
        $categoriaId = $primerProducto->getCategoria()->getId();
        return $this->redirectToRoute('productos', ['categoria' => $categoriaId]);
    }

    #[Route('/cesta', name: 'cesta')]
    public function mostrar_cesta(CestaCompra $cesta): Response
    {
        return $this->render('cesta/mostrar_cesta.html.twig', [
            'productos' => $cesta->get_productos(),
            'unidades' => $cesta->get_unidades()
        ]); //
    }

    #[Route('/actualizar', name: 'actualizar_cantidad', methods: ['POST'])]
    public function actualizar(Request $request, CestaCompra $cesta): Response
    {
        $producto_id = $request->request->get("producto_id");
        $cantidad = (int)$request->request->get("cantidad");
        $cesta->actualizar_unidades($producto_id, $cantidad);
        return $this->redirectToRoute('cesta');
    }

    #[Route('/eliminar', name: 'eliminar')]
    public function eliminar(Request $request, CestaCompra $cesta): RedirectResponse
    {
        $producto_id = $request->request->get("producto_id");
        $unidades = $request->request->get("unidades");
        $cesta->eliminar_producto($producto_id, $unidades);
        return $this->redirectToRoute('cesta');
    }
}