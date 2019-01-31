<?php

namespace App\Controller;

use App\Entity\Article;

use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractFOSRestController{

    /**
     * Lists all Articles.
     * @FOSRest\Get("/articles")
     * @return Response
     */
    public function getArticles() {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        
        // query for a single Product by its primary key (usually "id")
        $articles = $repository->findall();

        if ($articles) {
            $codeStatut = Response::HTTP_OK;
            $view = $this->view($articles, $codeStatut);
        } else {
            $codeStatut = Response::HTTP_BAD_GATEWAY;
            $view = $this->view(null, $codeStatut);
        }
        
        return $this->handleView($view);
    }

    /**
     * Lists all Articles.
     * @FOSRest\Get("/articles/{id}")
     * @param integer $id
     * @return Response
     */
    public function getArticleById($id) {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        
        // query for a single Product by its primary key (usually "id")
        $article = $repository->find($id);

        if ($article) {
            $codeStatut = Response::HTTP_OK;
            $view = $this->view($article, $codeStatut);
        } else {
            $codeStatut = Response::HTTP_BAD_GATEWAY;
            $view = $this->view(null, $codeStatut);
        }
        
        return $this->handleView($view);
    }
    
    /**
     * Create Article. 
     * @FOSRest\Post("/article")
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request){
        $name = $request->get('name');
        $description = $request->get('description');

        if(empty($name) || empty($description)){
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE); 
        }else {
            $data = new Article();
            $data->setName($name);
            $data->setDescription($description);
    
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
    
    
            $codeStatut = Response::HTTP_CREATED;
            $view = $this->view($data, $codeStatut);
    
            return $this->handleView($view);
        } 
    }
}
