<?php
 namespace  App\Controller;
 use App\Entity\Task;

 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\HttpFoundation\Session\SessionInterface;
 use Symfony\Component\Routing\Annotation\Route;
 use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 use Symfony\Component\HttpFoundation\Session\Session;
 use Symfony\Component\Form\Extension\Core\Type\TextType;
 use Symfony\Component\Form\Extension\Core\Type\TextareaType;
 use Symfony\Component\Form\Extension\Core\Type\SubmitType;
 use Doctrine\ORM\EntityManagerInterface;




 Class TodoController extends AbstractController
 {
   private $session;


//   function __construct( SessionInterface $session)
//   {
//     $this->session = $session;
//
//     $this->session->set('todos', [
//       new Task( 1568800232,"Sáng mai đi học","Vào lớp lúc 9h bắt đầu học là 9h30",false),
//       new Task( 1568800254,"Chiều đi đá banh","Sân vận động quốc gia Chảo lửa",false),
//       new Task( 1568800260,"Tối đi uống cf","Uống cf cho não nở ra",false),
//       new Task( 1568800260,"Tối đi uống cf","Uống cf cho não nở ra",false),
//     ]);
//
//   }

   public function createTaskForm()
   {

     $form = $this->createFormBuilder()
       ->add('Title', TextType::class , [
         'attr'=>[
           'placeholder' => 'title here...',
           'class'       => 'form-control',
           'style'       => 'padding: 10px; border-radius: 15px; width: 100%; margin-bottom: 10px;',
         ]
       ])
       ->add('Content', TextareaType::class, [
         'attr' => [
           'placeholder' => 'content here...',
           'class'       => 'input-group-prepend',
           'style'       => 'padding: 10px; border-radius: 15px; width: 100%; margin-bottom: 10px;',
           ]

         ])
       ->add('save', SubmitType::class, [
         'label' => 'Create Task',
         'attr' => [
           'class' => 'btn btn-success',
           'style' => 'border-radius: 20px; height: 40px; width: 150px; margin-bottom: 10px;',
         ]
         ])
       ->getForm();

        return $form;
   }

   private function getTodoList()
   {
     $todolist =  $this->getDoctrine()
       ->getRepository(Task::class)
       ->findAll();
     return $todolist;
   }

  /**
   * @Route("/", name = "index")
   * @Method({"GET"})
  */
   public function index(Request $request){

     $em =  $this->getDoctrine()->getManager();
     $todoList = $this->getTodoList();
     $request_data = null;

     $form = $this->createTaskForm();
     $form->handleRequest($request);

     if ($form->isSubmitted() && $form->isValid()) {
        $id_generated = date_timestamp_get(date_create());
        $request_data = $form->getData();
        $task = new Task( $request_data['Title'], $request_data['Content'],0);

        $em->persist($task);
        $em->flush();
        $todoList = $this->getTodoList();
        return $this->render('todo/index.html.twig', ["todos" => $todoList,"form" => $form->createView()]);
     }

     return $this->render('todo/index.html.twig', ["todos" => $todoList,"form" => $form->createView()]);
   }

   /**
    * @Route("/task/{id}", name = "task-detail")
    * @Method({"GET"})
    */
   public function taskdetail($id){
     $todolist = $this->getDoctrine()
       ->getRepository(Task::class)
       ->findAll();
     $taskdetail = null;
     foreach ($todolist as $task)
     {
       if($task->getId() == $id)
       {
         $taskdetail = $task;
       }
     }
     if($taskdetail !=null)
     {
       return $this->render('todo/taskdetail.html.twig', ["task" => $taskdetail]);
     }
     else
     {
       return new Response('<html><body><div style="text-align: center; margin-top: 100px;" ><h2>Task này chưa có nha!</h2></div></body></html>',404);
     }
   }

 }