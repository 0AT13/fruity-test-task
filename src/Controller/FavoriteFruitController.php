<?php

namespace App\Controller;

use App\Entity\FavoriteFruit;
use App\Helpers\NutritionSummary;
use App\Repository\FavoriteFruitRepository;
use App\Repository\FruitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class FavoriteFruitController extends AbstractController
{
    public function __construct(
        protected TranslatorInterface $translator,
    ) {
    }

    #[Route('/favorite/fruit', name: 'app_favorite_fruit')]
    public function index(Request $request, FavoriteFruitRepository $favoriteFruitRepository): Response
    {
        $query = $request->query;

        $favoriteFruits = $favoriteFruitRepository->findAll();

        $fruits = [];
        foreach ($favoriteFruits as $item) {
            $fruits[] = $item->getFruit();
        }

        $nutritionSummary = NutritionSummary::summary($fruits);

        return $this->render('favorite_fruit/index.html.twig', [
            'fruits' => $fruits,
            'nutritionSummary' => $nutritionSummary,
            'query' => $query->getIterator()->getArrayCopy()
        ]);
    }

    #[Route('/favorite/add', name: 'app_favorite_add')]
    public function add(Request $request, FruitRepository $fruitRepository, FavoriteFruitRepository $favoriteFruitRepository)
    {
        $fruitId = $request->query->getInt('fruitId');
        $fruit = $fruitRepository->findOneBy(['id' => $fruitId]);

        if ($fruit) {
            if (count($favoriteFruitRepository->findAll()) < 10) {
                if ($favoriteFruitRepository->findOneBy(['fruit' => $fruit]) == null) {
                    $favorite = new FavoriteFruit();
                    $favorite->setFruit($fruit);

                    $favoriteFruitRepository->save($favorite, true);

                    $this->addFlash('success', $this->translator->trans('backend.favorite_fruits.added'));
                }
            } else {
                $this->addFlash('danger', $this->translator->trans('backend.favorite_fruits.limit'));
            }
        } else {
            $this->addFlash('danger', $this->translator->trans('backend.utils.incorect_data'));
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/favorite/remove', name: 'app_favorite_remove')]
    public function remove(Request $request, FavoriteFruitRepository $favoriteFruitRepository)
    {
        $fruit = $request->query->getInt('fruitId');

        if (($favorite = $favoriteFruitRepository->findOneBy(['fruit' => $fruit])) != null) {
            $favoriteFruitRepository->remove($favorite, true);

            $this->addFlash('success', $this->translator->trans('backend.favorite_fruits.removed'));
        } else {
            $this->addFlash('danger', $this->translator->trans('backend.utils.incorect_data'));
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
