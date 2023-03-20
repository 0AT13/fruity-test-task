<?php

namespace App\Controller;

use App\Helpers\NutritionSummary;
use App\Repository\FamilyRepository;
use App\Repository\FruitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FruitController extends AbstractController
{
    #[Route('/', name: 'app_fruit')]
    public function index(Request $request, FruitRepository $fruitRepository, FamilyRepository $familyRepository): Response
    {
        $query = $request->query;

        $offset = $query->getInt('offset', 1);
        $paginator = $fruitRepository->getPaginator($offset - 1, $query);
        $total = count($fruitRepository->getPaginator($offset - 1, $query, true)) / FruitRepository::ITEM_PER_PAGE;

        $familyOptions = $familyRepository->findAllForSelect();

        $nutritionSummary = NutritionSummary::summary($paginator);

        return $this->render('fruit/index.html.twig', [
            'fruits' => $paginator,
            'current' => $offset,
            'total' => $total,
            'familyOptions' => $familyOptions,
            'nutritionSummary' => $nutritionSummary,
            'query' => $query->getIterator()->getArrayCopy()
        ]);
    }
}
