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

    #[Route('/actualizar', name: 'actualizar_cantidad', methods: ['POST'])]
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
        $pedido_id = null; // Inicialización para evitar errores en la plantilla si la cesta está vacía
        
        $usuario = $this->getUser(); // Obtiene el usuario autenticado
        $productos = $cesta->get_productos(); // Recupera productos de la sesión
        $unidades = $cesta->get_unidades(); // Recupera cantidades de la sesión

        if (count($productos) == 0) {
            // Valor 1 cuando no hay productos en la cesta
            $error = 1;
        } else {
            // Generamos un nuevo objeto Pedido con sus Setters
            $pedido = new Pedido();
            $pedido->setUsuario($usuario);
            $pedido->setFecha(new \DateTime());
            $pedido->setCoste($cesta->calcular_coste(null, $unidades));

            $em->persist($pedido);
            
            foreach ($productos as $codigo => $producto) {
                $pedidoProducto = new PedidoProducto();
                
                $productoGestionado = $em->getRepository(Producto::class)->find($producto->getId());
                
                if ($productoGestionado) {
                    $pedidoProducto->setProducto($productoGestionado);
                    $pedidoProducto->setUnidades($unidades[$codigo]);
                    $pedidoProducto->setPedido($pedido);

                    $em->persist($pedidoProducto);
                }
            }

            try {
                $em->flush();
                $pedido_id = $pedido->getId(); // Se asigna el ID tras guardar con éxito
            } catch (\Exception $ex) {
                dd($ex->getMessage());
                $error = 2; // Código de error para fallos de base de datos
            }
            
            if (!$error){
                // Obtenemos el ID del usuario de la sesión
                $usuario_login = $this->getUser()->getUserIdentifier();
                
                $usuario = $em->getRepository(Usuario::class)->findOneBy(['login' => $usuario_login]);
                
                $destination_email = $usuario->getEmail();
                        
                $email = (new TemplatedEmail())
                    ->from('gar.asat.96@gmail.com')
                    ->to(new Address('gasa692@g.educaand.es'))
                    ->subject('Confirmación de pedido' . $pedido->getId())

                    // indicamos la ruta de la plantilla
                    ->htmlTemplate('correo.html.twig')
                    ->locale('es')
                    // pasamos variables (clave => valor) a la plantilla
                    ->context([
                        'pedido_id' => $pedido->getId(), 'productos' => $cesta ->get_productos(), 'unidades' => $cesta->get_unidades(),
                        'coste' => $cesta->calcular_coste(),
                    ])
                ;
                $mailer->send($email);

            }
        }

        return $this->render('pedido/pedido.html.twig', [
            'error' => $error,
            'pedido_id' => $pedido_id
        ]);
    }
    
    #[Route('/historial', name: 'historial_pedidos')]
    public function historial(): Response
    {
        $usuario = $this->getUser();
        
        $pedidos = $usuario->getPedidos();
        
        return $this->render('pedido/historial.html.twig', [
            'pedidos' => $pedidos,
        ]);
    }
}