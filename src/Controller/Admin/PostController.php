<?php

namespace App\Controller\Admin; // le PostController est un espace de nom, toutes les constantes, fonctions et classes déclarées ici feront partie du namespace App\Controller\Admin

// L'opérateur "use" s'utilise pour appeler les classes et les interfaces afin de pouvoir inclure les fichiers nécessaire au bon fonctionnement du programme
use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// l'annotation prend plusieurs parametres: le chemin relatif de l'url, le nom de la route, 
// on indique une route de la classe qui va se répéter dans toutes les méthodes 

/**
 * @Route("/admin", name="admin_")
 */
class PostController extends AbstractController
// la classe PostController hérite de toutes les méthodes publiques et protégées, propriétés et constantes de la classe parente AbstractController,  
{
    /**
     * @Route("/posts", name="post_index")
     */
    public function index(PostRepository $postRepository): Response
    // méthode publique, accéssible dans tout le programme, prend en paramètre une variable de type objet PostRepository, et renvoie une reponse de type objet Response
    {
        $posts = $postRepository->findAll(); // injection de dépendances PostRepository dans la signature de la méthode index, la méthode findAll() est prédéfinie dans la classe PostRépository est renvoie toutes les lignes dans la base de données de l'entité Post qui vont être stockées dans tableau et assignées à la variable $posts

        // dd($posts); // die and dump s'utilise pour le debogage

        // la méthode render() génère le rendu du template twig et lui passe un tableau de variables
        return $this->render('admin/post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/add", name="post_add")
     */
    public function addPost(Request $request): Response
    // la méthode addPost prend en paramètre une variable de tyoe objet Request et retourne un objet Response
    {
        //instanciation d'un nouvel objet Post
        $post = new Post();
        // la méthode protégée createForm() de l'AbstractController  prend en paramètre le type du formulaire à créer et l'objet dans lequel vont être stockées les données, et retourne un formulaire qui est une instance de la classe PostFormType
        $form = $this->createForm(PostFormType::class, $post);
        // la méthode publique handleRequest() de FormInterface inspècte les données fournies dans le formulaire 
        $form->handleRequest($request);
        
        // si le formulaire a été envoyé et s'il a été validé
    if ($form->isSubmitted() && $form->isValid()) {
        // l'objet courant PostController récupere les infos de l'utilisateur grace à la méthode getUser() de l'interface UserInterface et il va les enregistrer avec la méthode publique setUser() dans l'instance de la classe Post $post
        $post->setUser($this->getUser());
        // l'instance $post appelle la méthode publique setActive(), en lui pasant le paramètre exigé : true ou false
        $post->setActive(false);
        // l'entityManager de Doctrine est appelé par l'objet courant
        $em = $this->getDoctrine()->getManager();
        // l'entityManager analyse les données de l'instance $post et prend certains décisions pour les persister, les synchroniser, et faire les reqûetes correspondantes 
        $em->persist($post);
        // dernière commande pour envoyer les modifications dans la base de données
        $em->flush();
        // renvoie une redirection vers la route 'home' soit page d'accueil en cas de succes
        return $this->redirectToRoute('home');
    }
    // la méthode render() génère le rendu du template twig et lui passe un tableau de variables, en premier parametre c'est le chemin relatif, l'instance $form appele la méthode createView() pour fabriquer le HTML et l'assigne à la variable 'form'

        return $this->render('admin/post/add.html.twig', [
            'form' => $form->createView(),
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
        $entityManager->persist($post); // on perenise les données en faisant un update
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
        $lastPosts = $postRepository->findLastPosts(2); // injection de dépendances PostRepository dans la signature de la méthode
        // dd($lastPosts);

        $oldPosts = $postRepository->findOldPosts(4); // injection de dépendances PostRepository dans la signature de la méthode
        // dd($oldPosts);
        return $this->render('post/view.html.twig', []);
    }
}
