<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin_")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/posts", name="post_index")
     */
    public function index(PostRepository $postRepository): Response
    {
        //
        $posts = $postRepository->findAll(); // injection de dépendances PostRepository dans la signature de la méthode
        // dd($posts);
        return $this->render('admin/post/index.html.twig', [
            'bg_image' => 'clean/assets/img/home-bg.jpg',
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/add", name="post_add")
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
        return $this->redirectToRoute('home');
    }

        return $this->render('admin/post/add.html.twig', [
            'form' => $form->createView(),
            'bg_image' => 'clean/assets/img/post-bg.jpg',
        ]);
    }

    /**
     * @Route("/post/activate/{id}", name="post_activate", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function activatePost(Post $post): Response
    {
        // dd($post);
        $post->setActive($post->getActive() ? false : true); // toggle buttton pour l'activation
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post); // on pernise les données en faisant un update
        $entityManager->flush();
        // retourne une réponse http au controleur et au JS qui a été éfectué la reqête asynchrone
        return new Response('true', 200);
    }

    /**
     * @Route("/post/update/{id}", name="post_update", requirements={"id"="\d+"})
     */
    public function updatePost(Post $post, Request $request): Response
    {
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $post->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Les modifications viennent d\'être enregistré');
            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/post/update.html.twig', [
            'form' => $form->createView(),
            'bg_image' => 'clean/assets/img/post-bg.jpg',
        ]);
    }

    /**
     * @Route("/post/delete/{id}", name="post_delete", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function deletePost(Post $post): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();
        $this->addFlash('succes', 'L\'article vient d\'être supprimé');
        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/test", name="test")
     */
    public function test(PostRepository $postRepository): Response
    {
        $lastPosts = $postRepository->findLastPosts(2); // injection de dépendances PostRepository dans la signature de la méthodeP
        // dd($lastPosts);

        $oldPosts = $postRepository->findOldPosts(4); // injection de dépendances PostRepository dans la signature de la méthode
        // dd($oldPosts);
        return $this->render('post/view.html.twig', []);
    }
}
