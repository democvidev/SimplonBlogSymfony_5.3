<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_index")
     */
    public function index(UserRepository $userRepository): Response
    {
        //
        $users = $userRepository->findAll(); // injection de dépendances UsertRepository dans la signature de la méthode
        // dd($posts);
        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    // /**
    //  * @Route("/user/add", name="user_add")
    //  */
    // public function addUser(Request $request): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserFormType::class, $user);
    //     $form->handleRequest($request);
        
    // if ($form->isSubmitted() && $form->isValid()) {
    //     $em = $this->getDoctrine()->getManager();
    //     $em->persist($user);
    //     $em->flush();
    //     $this->addFlash('success', 'Un nouveau membre vient d\'être ajouté');
    //     return $this->redirectToRoute('admin_home');
    // }

    //     return $this->render('admin/user/add.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }


    // /**
    //  * @Route("/user/update/{id}", name="post_update", requirements={"id"="\d+"})
    //  */
    // public function updateUser(User $user, Request $request): Response
    // {
    //     $form = $this->createForm(UserFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {            
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($user);
    //         $em->flush();
    //         $this->addFlash('success', 'Les modifications viennent d\'être enregistré');
    //         return $this->redirectToRoute('admin_home');
    //     }

    //     return $this->render('admin/user/update.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/User/delete/{id}", name="post_delete", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function deleteUser(User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('succes', 'L\'article vient d\'être supprimé');
        return $this->redirectToRoute('admin_home');
    }
   
}
