<?php

namespace App\Message;


class FetchNews{

 private $title;
 private $image;
 private $description;
 

 public function __construct(string $title,  string $description, string $image){
     $this->title = $title;
     $this->image = $image;
     $this->description = $description;
 }

 public function getTitle(): string{
     return $this->title;
 }

 public function getImage(): string{
    return $this->image;
  }

  public function getDescription(): string{
    return substr(strip_tags($this->description), 0, 255);
  }
}