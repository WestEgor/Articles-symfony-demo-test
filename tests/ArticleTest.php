<?php

namespace App\Tests;

use App\Entity\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{

    /** @test */
    public function firstTest()
    {
        $article = new Article();
        $article->setBody("kek");
        $this->assertEquals("kek", $article->getBody());
    }

}