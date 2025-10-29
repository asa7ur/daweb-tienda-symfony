<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Categoria;

final class BaseController extends AbstractController
{
    #[Route('/categorias', name: 'app_categorias')]
    public function mostrar_categorias(ManagerRegistry $em): Response
    {
        $categorias = $em ->getRepository(Categoria::class)->findAll();
        return $this->render('categorias/mostrar_categorias.html.twig', [
            'categorias' => $categorias,
        ]);
    }
}
