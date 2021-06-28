<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\Category;
use App\Form\PostFormType;
use App\Form\CategoryFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('admin/index.html.twig', []);
    }    

     /**
     * @Route("/post/add", name="add_post")
     */
    public function addPost(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        
    if ($form->isSubmitted() && $form->isValid()) {
        $post->setUser($this->getUser());
        $post->setActive(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        return $this->redirectToRoute('admin_home');
    }

        return $this->render('admin/post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
