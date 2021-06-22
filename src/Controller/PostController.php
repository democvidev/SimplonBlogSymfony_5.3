<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(PostRepository $postRepository): Response
    {
        // 
        $posts = $postRepository->findAll(); // injection de dépendances PostRepository dans la signature de la méthode
        // dd($posts);
        return $this->render('post/home.html.twig', [
            'bg_image' => 'clean/assets/img/home-bg.jpg',
            'posts' => $posts
        ]);
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

        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
            'bg_image' => 'clean/assets/img/post-bg.jpg',
        ]);
    }

    /**
     * ---Route("/post/{id}", name="post_view", methods={"GET"}, requirements={"id"="\d+"})
     * @Route("/post/{slug}", name="post_view", methods={"GET"})
     */
    public function view(Post $post): Response
    {
        // dd($post->getImage());
        // on récupère l'instance de l'entité Post, l'identifiant est convertit en instance de classe
        return $this->render('post/view.html.twig', [
            
            'bg_image' => $post->getImage(),
            'post' => $post
        ]);
    }

     
}
