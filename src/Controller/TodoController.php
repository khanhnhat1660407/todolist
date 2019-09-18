<?php
 namespace  App\Controller;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\HttpFoundation\Session\SessionInterface;
 use Symfony\Component\Routing\Annotation\Route;
 use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 use Symfony\Component\HttpFoundation\Session\Session;
 use Symfony\Component\Form\Extension\Core\Type\TextType;
 use Symfony\Component\Form\Extension\Core\Type\TextareaType;
 use Symfony\Component\Form\Extension\Core\Type\SubmitType;






 Class TodoController extends AbstractController
 {
   private $session;

   function RandomString()
   {
     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
     $randstring = '';
     for ($i = 0; $i < 10; $i++) {
       $randstring = $characters[rand(0, strlen($characters))];
     }
     return $randstring;
   }


   function __construct( SessionInterface $session)
   {
     $this->session = $session;
     $this->session->set('todos', [
       (object)[
         'id' => 1568800232,
         'title' => "Sáng mai đi học",
         'content' => "Vào lớp lúc 9h bắt đầu học là 9h30",
         'status' => false
       ],
       (object)[
         'id' => 1568800254,
         'title' => "Tối đi cua gái",
         'content' => "Cua gái thì mới có bồ được",
         'status' => false
       ],
       (object)[
         'id' => 1568800260,
         'title' => "Chiều đi tiễn thằng chiến ở sân bay",
         'content' => "Nó bay lúc 5h, sân quốc tế",
         'status' => false
       ],

     ]);
   }

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

        return $form->createView();
   }

  /**
   * @Route("/", name = "index")
   * @Method({"GET"})
  */
   public function index(){
     $form = $this->createTaskForm();
//     $string = date_timestamp_get(date_create());
     return $this->render('todo/index.html.twig', ["todos" => $this->session->get('todos'),"form" => $form]);
   }

   /**
    * @Route("/task/{id}", name = "task-detail")
    * @Method({"GET"})
    */
   public function taskdetail($id){
     $todolist = $this->session->get('todos');
     $taskdetail = null;
     foreach ($todolist as $task)
     {
       if($task->id == $id)
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