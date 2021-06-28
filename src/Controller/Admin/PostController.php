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
     * @Route("/post", name="post_index")
     */
    public function index(PostRepository $postRepository): Response
    {
        // 
        $posts = $postRepository->findAll(); // injection de dépendances PostRepository dans la signature de la méthode
        // dd($posts);
        return $this->render('admin/post/index.html.twig', [
            'bg_image' => 'clean/assets/img/home-bg.jpg',
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/post/activate/{id}", name="post_activate", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function activatePost(Post $post): Response
    {
        // dd($post);
        $post->setActive(($post->getActive()) ? false : true); // toggle buttton pour l'activation
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post); // on pernise les données en faisant un update
        $entityManager->flush();
        // retourne une réponse http au controleur et au JS qui a été éfectué la reqête asynchrone
        return new Response("true", 200);
        // return $this->json(['code' => 200, 'message' => 'Like bien ajouté',], 200);
    }


    /**
     * @Route("/post/update/{id}", name="post_update", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function updatePost(Post $post, PostRepository $postRepository): Response
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
