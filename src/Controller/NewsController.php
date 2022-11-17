<?php
  namespace App\Controller;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use App\Entity\Article;
  use Doctrine\Persistence\ManagerRegistry;

  class NewsController extends AbstractController
  {
      /**
       * @Route("/{page}", name="index", requirements={"page"="\d+"})
      */
      public function news(ManagerRegistry $doctrine, $page = 1): Response
      {

          $em = $doctrine->getManager();
          $articles = $em
            ->getRepository(Article::class);
          // build the query for the doctrine paginator
          $query = $articles->createQueryBuilder('u')
          ->orderBy('u.date_added', 'DESC')
          ->getQuery();

          $pageSize = 10;

          // load doctrine Paginator
          $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

            // you can get total items
          $totalItems = count($paginator);

          // get total pages
          $pagesCount = ceil($totalItems / $pageSize);

          // now get one page's items:
          $paginator
          ->getQuery()
          ->setFirstResult($pageSize * ($page-1)) // set the offset
          ->setMaxResults($pageSize); // set the limit
                    
       

          return $this->render('news.html.twig', [
           // this array defines the variables passed to the template,
           // where the key is the variable name and the value is the variable value
           // (Twig recommends using snake_case variable names: 'foo_bar' instead of 'fooBar')
            'news' => $paginator,
            'maxPages'=> $pagesCount,
            'thisPage' => $page
          ]);
      }


     
  }
?>