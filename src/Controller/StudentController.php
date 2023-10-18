<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\EditStudentType;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/StudentController.php',
        ]);
    }

    #[Route('/all', name: 'list')]

    public function showStudents(StudentRepository $studentRepository): Response
    {
        $students = $studentRepository->findAll();

        return $this->render('student/student.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/add-student', name: 'add_student')] 

    public function addStudent(Request $request): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();

            $this->addFlash('success', 'Student added successfully');

            return $this->redirectToRoute('list');
        }

        return $this->render('student/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/edit-student/{id}', name: 'edit_student')]
    public function editStudent(Request $request, Student $id): Response
    {
        $form = $this->createForm(EditStudentType::class, $id); 
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
    
            $this->addFlash('success', 'Student updated successfully');
    
            return $this->redirectToRoute('list'); 
        }
    
        return $this->render('student/edit.html.twig', [
            'form' => $form->createView(),
            'student' => $id, 
        ]);
    }
    #[Route('/delete-student/{id}', name: 'delete_student')]


   public function deleteStudent(Request $request, Student $student): Response
   {
       if ($request->isMethod('POST')) {
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->remove($student);
           $entityManager->flush();

           $this->addFlash('success', 'Student deleted successfully');

           return $this->redirectToRoute('list');
       }

       return $this->render('student/delete.html.twig', [
           'student' => $student,
       ]);
   }

}
