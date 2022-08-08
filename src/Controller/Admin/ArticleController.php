<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('admin/article/index.html.twig', [
            'articles' => $articleRepository->findBy(
                [],
                [
                    'date_created' => 'desc'
                ]
            ),
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setDateCreated(new \DateTime());

            $this->saveArticle($article, $form, $articleRepository);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->saveArticle($article, $form, $articleRepository);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
            $image = $this->getParameter('upload_directory') . '/' . $article->getCover();
            unlink($image);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param \App\Entity\Article $article
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \App\Repository\ArticleRepository $articleRepository
     * @throws \Exception
     */
    private function saveArticle(Article $article, FormInterface $form, ArticleRepository $articleRepository)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $form['image']->getData();

        if ($uploadedFile) {
            $newFilename = $this->uploadImage($uploadedFile);
            $article->setCover($newFilename);
        }

        $articleRepository->add($article, true);
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return string
     */
    private function uploadImage(UploadedFile $uploadedFile): string
    {
        $destination = $this->getParameter('upload_directory');

        $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
        try {
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            return $newFilename;
        } catch (FileException $e) {
            throw new $e;
        }
    }
}
