<?php
  namespace App\Controller;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use App\Entity\Article;
  use Doctrine\Persistence\ManagerRegistry;

  class ArticleController extends AbstractController
  {
      /**
       * @Route("/post/{id}/", name="post", requirements={"id"="\d+"})
      */
      public function post(ManagerRegistry $doctrine, $id): Response{
           $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_MODERATOR'], null, 'User tried to access a page without having ROLE_ADMIN');
   
           $em = $doctrine->getManager();
           $repo = $em
            ->getRepository(Article::class);
            $obj = $em
            ->getRepository(Article::class)
            ->findOneBy(array('id' => $id));

          return $this->render('article.html.twig', [
            // this array defines the variables passed to the template,
            // where the key is the variable name and the value is the variable value
            // (Twig recommends using snake_case variable names: 'foo_bar' instead of 'fooBar')
             'article' => $obj
           ]);
      }


      /**
       * @Route("/post/delete/{id}/", name="delete_post", requirements={"id"="\d+"})
      */
      #[IsGranted('ROLE_ADMIN')]
      public function delete(ManagerRegistry $doctrine, $id): Response{
       $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

       $em = $doctrine->getManager();
       $repo = $em
        ->getRepository(Article::class);
        $article = $em 
        ->getRepository(Article::class)
        ->findOneBy(array('id' => $id));
        $em->remove($article);
        $em->flush();
       

        return $this->render('deleteConfirmation.html.twig', [
         // this array defines the variables passed to the template,
         // where the key is the variable name and the value is the variable value
         // (Twig recommends using snake_case variable names: 'foo_bar' instead of 'fooBar')
        ]);
      }
  }
?>