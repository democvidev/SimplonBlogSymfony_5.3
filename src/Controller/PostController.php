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
        // injection de dépendances PostRepository dans la signature de la méthode
        // $lastPosts = $postRepository->findLastPosts(); 
        $lastPosts = $this->getDoctrine()->getRepository(Post::class)->findBy([
            'active' => 1
        ], ['createdAt' => 'desc']);
        $oldPosts = $postRepository->findOldPosts();

        return $this->render('post/home.html.twig', [
            'bg_image' => 'clean/assets/img/home-bg.jpg',
            'lastPosts' => $lastPosts,
            'oldPosts' => $oldPosts
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
        return $this->redirectToRoute('home');
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
    public function view(Post $post, PostRepository $postRepository): Response
    {
        $oldPosts = $postRepository->findOldPosts();
        // dd($post->getImage());
        // on récupère l'instance de l'entité Post, l'identifiant est convertit en instance de classe
        return $this->render('post/view.html.twig', [
            
            'bg_image' => $post->getImage(),
            'singlePost' => $post,
            'oldPosts' => $oldPosts
        ]);
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
