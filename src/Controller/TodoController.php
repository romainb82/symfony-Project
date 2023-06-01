<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoFilterType;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo_index', methods: ['GET', 'POST'])]
    public function index(Request $request, TodoRepository $todoRepository): Response
    {
        $form = $this->createForm(TodoFilterType::class);
        $form->handleRequest($request);

        //Filtre le status
        if ($form->isSubmitted() && $form->isValid()) {
            $check = $form->get('status')->getData();
            //dump($check);
            $critere = [];
            if($check){
                $critere = ["status" => $check];
            }
            return $this->render('todo/index.html.twig', [
                'todos' => $todoRepository->findBy($critere, []),
                'form' => $form->createView()
            ]);
        } 

        //Filtre le chaque champs quand on clique dessus
        $order = $request->query->get('order') == 'ASC' ? 'DESC' : 'ASC';
        $orderBy = $request->query->get('orderby');
        if(isset($order) && isset($orderBy)){
            $criteria = [$orderBy => $order];
            return $this->render('todo/index.html.twig', [
                'todos' => $todoRepository->findBy([], $criteria),
                'order' => $order,
                'form' => $form->createView()
            ]);
        } else {
            return $this->render('todo/index.html.twig', [
                'todos' => $todoRepository->findAll(),
                'form' => $form->createView()
            ]);
        }
        
    }


    #[Route('/new', name: 'app_todo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TodoRepository $todoRepository): Response
    {
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todoRepository->save($todo, true);

            return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('todo/new.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_todo_show', methods: ['GET'])]
    public function show(Todo $todo): Response
    {
        return $this->render('todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_todo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Todo $todo, TodoRepository $todoRepository): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todoRepository->save($todo, true);

            return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('todo/edit.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_todo_delete', methods: ['POST'])]
    public function delete(Request $request, Todo $todo, TodoRepository $todoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$todo->getId(), $request->request->get('_token'))) {
            $todoRepository->remove($todo, true);
        }

        return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
    }
}
