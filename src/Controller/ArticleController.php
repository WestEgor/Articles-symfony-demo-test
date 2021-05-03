<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\Type\ArticlesType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'articles', methods: ['get'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    #[ROUTE('/article/{id}', name: 'article_show', methods: ['post', 'get'])]
    public function show(int $id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository
            ->find($id);

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }

    #[ROUTE('/save_article', name: 'save_article', methods: ['post', 'get'])]
    public function createArticle(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticlesType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('articles');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[ROUTE('/edit_article/{id}', name: 'edit_article', methods: ['post', 'get'])]
    public function updateArticle(Request $request, int $id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);

        $form = $this->createForm(ArticlesType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('articles');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[ROUTE('/delete_article/{id}', name: 'article_delete', methods: ['delete', 'get'])]
    public function deleteArticle(int $id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        $response = new Response();
        return $response->send();
    }


}
