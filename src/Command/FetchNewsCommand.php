<?php

namespace App\Command;

use App\Repository\ArticleRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\FetchNews;

class FetchNewsCommand extends Command{
   private $articleRepository;
   private $client;
   private $bus;
   protected static $defaultName = 'app:news:fetch';

   public function __construct(ArticleRepository $articleRepository, HttpClientInterface $client, MessageBusInterface $bus)
   {
       $this->articleRepository = $articleRepository;
       $this->client = $client;
       $this->bus = $bus;
       parent::__construct();
   }

   protected function configure()
   {
       $this
           ->setDescription('Fetches news article from zero.pindula.co.zw')
       ;
   }

   protected function execute(InputInterface $input, OutputInterface $output): int
   {
       $io = new SymfonyStyle($input, $output);

       $response = $this->client->request(
          'GET',
          'https://zero.pindula.co.zw/api/posts/'
       );

       $statusCode = $response->getStatusCode();

       if($statusCode == 200){
          $resArray = $response->toArray();
          foreach($resArray['results'] as $article){
               $this->bus->dispatch(new FetchNews($title=$article['title'], $description=$article['excerpt'], $image=$article['image']));
          }
       }

       $io->success(sprintf('Successfully Updated Article'));

       return 0;
   }

}