<?php

namespace App\Controller;

use App\Repository\FruitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FruitController extends AbstractController
{
    public function __construct(
    ) {
    }

    #[Route('/', name: 'app_fruit')]
    public function index(Request $request, FruitRepository $fruitRepository): Response
    {
        $offset = max(1, $request->query->getInt('offset', 1));
        $paginator = $fruitRepository->getPaginator($offset - 1);
        $total = count($fruitRepository->findAll()) / FruitRepository::ITEM_PER_PAGE;

        return $this->render('fruit/index.html.twig', [
            'fruits' => $paginator,
            'current' => $offset,
            'total' => $total,
        ]);
    }
}
