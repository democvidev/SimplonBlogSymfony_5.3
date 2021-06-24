<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\Category;
use App\Form\PostFormType;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category_index")
     */
    public function indexCategory(
        CategoryRepository $categoryRepository
    ): Response {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/category/add", name="add_category")
     */
    public function addCategory(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);
        // verifie si on est dans le cas de submit et que les données correspondent aux critères de validation
        if ($form->isSubmitted() && $form->isValid()) {
            // connexion à l'ORM Doctrine, entityManager
            $em = $this->getDoctrine()->getManager();
            // fonction intéligente qui decide la requête à faire (SELECT, UPDATE)
            $em->persist($category);
            // exécution de la requête
            $em->flush();
            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
