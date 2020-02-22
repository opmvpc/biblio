<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ImporterBibtexService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImporterBibtexServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test de la création d'un objet ImporterBibtexService
     *
     * @return void
     */
    public function testCreateService()
    {
        $bibtex = static::getBibtexArticle();

        $service = new ImporterBibtexService($bibtex);

        $this->assertObjectHasAttribute('bibtex', $service);
        $this->assertEquals($bibtex, $service->bibtex());
    }

    /**
     * test du parsing d'une string bibtex
     *
     * @return void
     */
    public function testParseBibtex()
    {
        $bibtex = static::getBibtexArticle();
        $service = new ImporterBibtexService($bibtex);

        $articles = $this->invokeMethod($service, 'parse', array('passwordToCrypt'), []);
        $this->assertEquals($bibtex, $articles[0]['_original']);
        $this->assertEquals('article', $articles[0]['type']);
        $this->assertEquals('{An introduction to object-oriented programming with a didactic microworld: objectKarel}', $articles[0]['title']);
        $this->assertEquals('Xinogalos2006', $articles[0]['citation-key']);
    }

    /**
     * test de la sauvegarde d'un article en db
     *
     * @return void
     */
    public function testSaveBibtex()
    {
        $bibtex = static::getBibtexArticle();
        $service = new ImporterBibtexService($bibtex);

        $service->save();

        $this->assertDatabaseHas('articles', ['titre' => 'An introduction to object-oriented programming with a didactic microworld: objectKarel']);
        $this->assertDatabaseHas('keywords', ['nom' => 'Teaching/learning strategies']);
    }

    /**
     * test de la sauvegarde d'un article en db
     *
     * @return void
     */
    public function testSaveMixed()
    {
        $bibtex = static::getBibtexMixed();
        $service = new ImporterBibtexService($bibtex);

        $service->save();
        $this->assertDatabaseHas('articles', ['titre' => 'Using Visual Programming Language for Remedial Instruction: Comparison of Alice and Scratch']);
        $this->assertDatabaseHas('articles', ['titre' => 'An Interactive Bomberman Game-Based Teaching/ Learning Tool for Introductory C Programming']);
        $this->assertDatabaseHas('articles', ['titre' => 'Current Trends in On-line Games for Teaching Programming Concepts to Primary School Students']);
        $this->assertDatabaseHas('articles', ['titre' => 'An introduction to object-oriented programming with a didactic microworld: objectKarel']);
        $this->assertDatabaseHas('articles', ['titre' => 'Exploration of modularity and reusability of domain-specific languages: an expression DSL in MetaMod']);
    }

    private static function getBibtexArticle(): string
    {
        return <<<EOT
@article{Xinogalos2006,
abstract = {The objects-first strategy to teaching programming has prevailed over the imperative-first and functional-first strategies during the last decade. However, the objects-first strategy has created added difficulties to both the teaching and learning of programming. In an attempt to confront these difficulties and support the objects-first strategy we developed a novel programming environment, objectKarel, which uses the language Karel++. The design of objectKarel was based on the results of the extended research that has been carried out about novice programmers. What differentiates it from analogous environments is the fact that it combines features that have been used solely in them: incorporated e-lessons and hands-on activities; an easy to use structure editor for developing/editing programs; program animation; explanatory visualization; highly informative and friendly error messages; recordability. In this paper, we present the didactic rationale that dictated the design of objectKarel and the features of the environment, including the e-lessons. In addition, we present the results from the use of objectKarel in the classroom and the results of the students' assessment of the environment. {\textcopyright} 2004.},
author = {Xinogalos, Stelios and Satratzemi, Maya and Dagdilelis, Vassilios},
doi = {10.1016/j.compedu.2004.09.005},
issn = {03601315},
journal = {Computers and Education},
keywords = {Pedagogical issues,Programming and programming languages,Teaching/learning strategies},
month = {9},
number = {2},
pages = {148--171},
title = {{An introduction to object-oriented programming with a didactic microworld: objectKarel}},
volume = {47},
year = {2006}
}
EOT;
    }

    private static function getBibtexMixed(): string
    {
        return <<<EOT
@incollection{Chang2013,
    author = {Chang, Ching and Lin, Yu-Ling and Chang, Chih-Kai},
    doi = {10.1007/978-3-642-41175-5_23},
    pages = {224--233},
    publisher = {Springer, Berlin, Heidelberg},
    title = {{Using Visual Programming Language for Remedial Instruction: Comparison of Alice and Scratch}},
    url = {http://link.springer.com/10.1007/978-3-642-41175-5\_23},
    year = {2013}
}
@incollection{Wong2007,
    address = {Berlin, Heidelberg},
    author = {Wong, Wai-Tak and Chou, Yu-Min},
    booktitle = {Technologies for E-Learning and Digital Entertainment},
    doi = {10.1007/978-3-540-73011-8_43},
    pages = {433--444},
    publisher = {Springer Berlin Heidelberg},
    title = {{An Interactive Bomberman Game-Based Teaching/ Learning Tool for Introductory C Programming}},
    url = {http://link.springer.com/10.1007/978-3-540-73011-8_43},
    year = {2007}
}
@incollection{Giannakoulas2019,
    author = {Giannakoulas, Andreas and Xinogalos, Stelios},
    doi = {10.1007/978-3-030-20954-4_5},
    month = {6},
    pages = {62--78},
    publisher = {Springer, Cham},
    title = {{Current Trends in On-line Games for Teaching Programming Concepts to Primary School Students}},
    url = {http://link.springer.com/10.1007/978-3-030-20954-4_5},
    year = {2019}
}
@article{Xinogalos2006,
    abstract = {The objects-first strategy to teaching programming has prevailed over the imperative-first and functional-first strategies during the last decade. However, the objects-first strategy has created added difficulties to both the teaching and learning of programming. In an attempt to confront these difficulties and support the objects-first strategy we developed a novel programming environment, objectKarel, which uses the language Karel++. The design of objectKarel was based on the results of the extended research that has been carried out about novice programmers. What differentiates it from analogous environments is the fact that it combines features that have been used solely in them: incorporated e-lessons and hands-on activities; an easy to use structure editor for developing/editing programs; program animation; explanatory visualization; highly informative and friendly error messages; recordability. In this paper, we present the didactic rationale that dictated the design of objectKarel and the features of the environment, including the e-lessons. In addition, we present the results from the use of objectKarel in the classroom and the results of the students' assessment of the environment. {\textcopyright} 2004.},
    author = {Xinogalos, Stelios and Satratzemi, Maya and Dagdilelis, Vassilios},
    doi = {10.1016/j.compedu.2004.09.005},
    issn = {03601315},
    journal = {Computers and Education},
    keywords = {Pedagogical issues,Programming and programming languages,Teaching/learning strategies},
    month = {9},
    number = {2},
    pages = {148--171},
    title = {{An introduction to object-oriented programming with a didactic microworld: objectKarel}},
    volume = {47},
    year = {2006}
}
@article{Sutii2018,
    abstract = {Language-oriented programming (LOP) advocates a way of creating software systems that starts from the development of a domain-specific language (DSL). The DSL is geared towards solving computational problems in a particular domain. Developers then use the DSL to express configurations, rules, algorithms or knowledge for this particular domain at higher levels of abstraction than those achievable with a general-purpose programming language. Achieving the vision of LOP requires methods to ease the creation and the reuse of DSLs. One of the most likely technologies to achieve the vision of LOP are the projectional language workbenches because of the flexibility they add in DSL notation and DSL modularity. Modularity, in particular, is a key factor in easing the creation and reuse of DSLs. We have previously designed a new method and associated meta-tools, called MetaMod, for the creation of modular and reusable DSLs. The goal in this article is to demonstrate what the advantages of MetaMod are in terms of creating modular and reusable DSLs compared to the state-of-the-art projectional language workbench Jetbrains MPS. To this end, we took a comprehensive expression language, the iets3 expression DSL written in Jetbrains MPS, and redefined a fragment of it in MetaMod; we use part of this reimplemented expression language as a running example in the article. This allowed us to make a better comparison with creating DSLs in MPS. Moreover, in the process, we also highlighted the main features of MetaMod concerning modularity and reuse. As a result of the comparison we underline the main advantage that MetaMod brings in the implementation of modular and reusable DSLs, that is, the possibility to create smaller, more conceptually cohesive DSLs. This makes DSLs more fit for reuse. Furthermore, we present an extensive overview of related work regarding features of language tools for creating modular and reusable DSLs.},
    author = {Şut{\^{i}}i, Ana Maria and van den Brand, Mark and Verhoeff, Tom},
    doi = {10.1016/J.CL.2017.07.004},
    issn = {1477-8424},
    journal = {Computer Languages, Systems {\&} Structures},
    month = {1},
    pages = {48--70},
    publisher = {Pergamon},
    title = {{Exploration of modularity and reusability of domain-specific languages: an expression DSL in MetaMod}},
    url = {https://www.sciencedirect.com/science/article/abs/pii/S1477842417300404},
    volume = {51},
    year = {2018}
}
EOT;
    }
}
