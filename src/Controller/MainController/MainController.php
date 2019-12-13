<?php

namespace App\Controller\MainController;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
  /**
  * @Route("/files/get", name="show_files")
  */
  public function showFiles()
  {
    $rep = $this->getDoctrine()->getRepository(Task::class);
    $tasks = $rep->findAll();
    $data = [];
    $i = 0;
    foreach($tasks as $task)
    {
      $data[$i] = $task;
      $i++;
    }
    return $this->render('HomePage/HomePage.html.twig',[
      'data' => $data
    ]);
  }
  /**
  * @Route("/files/create", name="create_task")
  */
  public function createTasks(Request $request)
  {
    $task = new Task();
    $form = $this->createFormBuilder($task)->add('title', TextType::class, array('attr' => array('class'=>'form-control')))
                                           ->add('description', TextareaType::class, array('attr'=> array('class'=>'form-control')))
                                           ->add('date', DateType::class, array('label'=>'Date', 'attr'=>array('class' => 'form-control')))
                                           ->add('save', SubmitType::class, array('label'=>'Create', 'attr'=>array('class' => 'btn btn-dark float-left mt-3', 'style'=>'width:48%;')))
                                           ->add('reset', ResetType::class, array('label'=>'Reset', 'attr'=>array('class'=> 'btn btn-dark float-right mt-3',  'style'=>'width:48%;')
                                         ))
                                         ->getForm();
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
        $task = $form -> getData();
        $entityManager=$this->getDoctrine()->getManager();
        $entityManager -> persist($task);
        $entityManager -> flush();
        return $this->redirectToRoute('HomePage/HomePage.html.twig');
    }
    return $this->render('Tasks/Tasks.html.twig', [
      'form'=>$form->createView()
    ]);
  }
}
?>
