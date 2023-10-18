<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\AddClassType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }

    #[Route('/classrooms', name: 'classrooms_index')]
public function show(): Response
{
    $classrooms = $this->getDoctrine()->getRepository(Classroom::class)->findAll();

    return $this->render('classroom/index.html.twig', [
        'classrooms' => $classrooms,
    ]);
}




#[Route('/classroom/edit/{id}', name: 'classroom_edit')]
public function edit(Classroom $classroom, Request $request): Response
{
    $form = $this->createForm(ClassroomType::class, $classroom);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('classrooms_index');
    }

    return $this->render('classroom/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/classrooms', name: 'classrooms_index')]
public function showAll(): Response
{
    $classrooms = $this->getDoctrine()->getRepository(Classroom::class)->findAll();

    return $this->render('classroom/index.html.twig', [
        'classrooms' => $classrooms,
    ]);
}



#[Route('/classroom/add', name: 'classroom_add')]
public function add(Request $request): Response
{
    $classroom = new Classroom();
    $form = $this->createForm(AddClassType::class, $classroom);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($classroom);
        $entityManager->flush();

        return $this->redirectToRoute('classrooms_index');
    }

    return $this->render('classroom/add.html.twig', [
        'form' => $form->createView(),
    ]);
}



#[Route('/classroom/delete/{id}', name: 'classroom_delete')]
public function delete(Classroom $classroom): JsonResponse
{
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($classroom);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Classroom deleted']);
}


}
