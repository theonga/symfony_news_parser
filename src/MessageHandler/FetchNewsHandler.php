<?php

namespace App\MessageHandler;
use App\Message\FetchNews;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use \Datetime;

class FetchNewsHandler implements MessageHandlerInterface{
   
 private $doctrine;

 

 public function __construct(ManagerRegistry $doctrine){
     $this->doctrine = $doctrine;
 }

   public function __invoke(FetchNews $fetchNews){
     $em = $this->doctrine->getManager();

     $article = new Article();

     $obj = $em
        ->getRepository(Article::class)
        ->findOneBy(array('title' => $fetchNews->getTitle()));

     if($obj){
        $obj->setPicture($fetchNews->getImage());
        $obj->setDescription($fetchNews->getDescription());
        $now = new DateTime();
        $article->setDateUpdated($now);
        $em->flush();
     }else{
        $article->setTitle($fetchNews->getTitle());
        $article->setPicture($fetchNews->getImage());
        $article->setDescription($fetchNews->getDescription());
   
        $now = new DateTime();
   
        $article->setDateAdded($now);
        $article->setDateUpdated($now);
  
       // tells Doctrine you want to (eventually) save the Article (no queries yet)
       $em->persist($article);
   
       // actually executes the queries (i.e. the INSERT query)
       $em->flush();
     }   
   }
}