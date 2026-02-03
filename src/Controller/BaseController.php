<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categoria;
use App\Entity\Usuario;
use App\Entity\Producto;
use App\Entity\Pedido;
use App\Entity\PedidoProducto;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
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
        // Recupera y muestra todas las categorías disponibles
        $categorias = $em->getRepository(Categoria::class)->findAll();
        return $this->render('categorias/mostrar_categorias.html.twig', [
            'categorias' => $categorias,
        ]);
    }

    #[Route('/productos/{categoria}', name: 'productos', defaults: ['categoria' => null])]
    public function mostrar_productos(EntityManagerInterface $em, ?int $categoria = null): Response
    {
        // Obtiene y muestra los productos de una categoría específica
        $categoriaObj = $em->getRepository(Categoria::class)->find($categoria);
        $productos = $categoriaObj->getProductos();
        return $this->render('productos/mostrar_productos.html.twig', [
            'productos' => $productos,
        ]);
    }

    #[Route('/anadir', name: 'anadir')]
    public function anadir_productos(EntityManagerInterface $em, Request $request, CestaCompra $cesta): RedirectResponse
    {
        // Procesa el formulario para añadir múltiples productos a la cesta
        $productos_ids = $request->request->all("productos_ids");
        $unidades = $request->request->all("unidades");
        $productos = $em->getRepository(Producto::class)->findBy(['id' => $productos_ids]);
        $cesta->cargar_productos($productos, $unidades);

        // Redirige de vuelta a la vista de la categoría del primer producto añadido
        $primerProducto = array_values($productos)[0];
        $categoriaId = $primerProducto->getCategoria()->getId();
        return $this->redirectToRoute('productos', ['categoria' => $categoriaId]);
    }

    #[Route('/cesta', name: 'cesta')]
    public function mostrar_cesta(CestaCompra $cesta): Response
    {
        // Renderiza la vista con el contenido actual de la cesta
        return $this->render('cesta/mostrar_cesta.html.twig', [
            'productos' => $cesta->get_productos(),
            'unidades' => $cesta->get_unidades()
        ]);
    }

    #[Route('/actualizar', name: 'actualizar', methods: ['POST'])]
    public function actualizar(Request $request, CestaCompra $cesta): Response
    {
        // Actualiza la cantidad o elimina si es 0 (unifica la lógica de borrar)
        $producto_id = $request->request->get("producto_id");
        $cantidad = (int)$request->request->get("cantidad");
        $cesta->actualizar_unidades($producto_id, $cantidad);
        return $this->redirectToRoute('cesta');
    }
    
    #[Route('/pedido', name: 'pedido')]
    public function pedido(EntityManagerInterface $em, CestaCompra $cesta, MailerInterface $mailer): Response
    {
        $error = 0;
        $pedido_id = null;

        $usuario = $this->getUser(); 
        $productos = $cesta->get_productos(); 
        $unidades = $cesta->get_unidades(); 

        if (count($productos) == 0) {
            $error = 1;
            $pedido = null;
        } else {
            $pedido = new Pedido();
            $pedido->setUsuario($usuario);
            $pedido->setFecha(new \DateTime());
            // Usamos el coste calculado por la cesta
            $pedido->setCoste($cesta->calcular_coste());

            $em->persist($pedido);

            foreach ($productos as $codigo => $producto) {
                $pedidoProducto = new PedidoProducto();

                // Buscamos el producto en la base de datos para asegurar la persistencia
                $productoGestionado = $em->getRepository(Producto::class)->find($producto->getId());

                if ($productoGestionado) {
                    $pedidoProducto->setProducto($productoGestionado);
                    $pedidoProducto->setUnidades($unidades[$codigo]);
                    $pedidoProducto->setPedido($pedido);

                    $em->persist($pedidoProducto);

                    $pedido->addPedidoProducto($pedidoProducto);
                }
            }

            try {
                $em->flush();
                $pedido_id = $pedido->getId(); 
            } catch (\Exception $ex) {
                $error = 2; 
            }

            if (!$error){
                $destination_email = $usuario->getEmail();

                $resumenProductos = [];
                foreach ($productos as $codigo => $producto) {
                    $resumenProductos[] = [
                        'nombre' => $producto->getNombreCorto(),
                        'precio' => $producto->getPrecio(),
                        'unidades' => $unidades[$codigo]
                    ];
                }

                $email = (new TemplatedEmail())
                    ->from('gar.asat.96@gmail.com')
                    ->to(new Address($destination_email))
                    ->subject('Confirmación de pedido #' . $pedido->getId())
                    ->htmlTemplate('correo.html.twig')
                    ->context([
                        'pedido_id' => $pedido->getId(), 
                        'productos' => $resumenProductos,
                        'coste' => $pedido->getCoste(),
                    ]);

                $mailer->send($email);
            }
        }

        return $this->render('pedido/pedido.html.twig', [
            'error' => $error,
            'pedido_id' => $pedido_id,
            'pedido' => $pedido
        ]);
    }
    
    #[Route('/historial', name: 'historial')]
    public function historial(): Response
    {
        $usuario = $this->getUser();
        
        $pedidos = $usuario->getPedidos();
        
        return $this->render('pedido/historial.html.twig', [
            'pedidos' => $pedidos,
        ]);
    }
}