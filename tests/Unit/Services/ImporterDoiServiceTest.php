<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ImporterDoiService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImporterDoiServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test de la création d'un objet ImporterBibtexService
     *
     * @return void
     */
    public function testCreateService()
    {
        $doi = static::getDoiArticle();

        $service = new ImporterDoiService($doi);

        $this->assertObjectHasAttribute('doi', $service);
        $this->assertEquals($doi, $service->doi());
    }

    /**
     * test de la création d'un objet ImporterBibtexService
     *
     * @return void
     */
    public function testGetBibtex()
    {
        $doi = static::getDoiArticle();

        $service = new ImporterDoiService($doi);

        $this->assertEquals(static::getBibtexArticle(), $service->getBibtex());
    }

    /**
     * test de la sauvegarde d'un article en db
     *
     * @return void
     */
    public function testSaveBibtex()
    {
        $doi = static::getDoiArticle();
        (new ImporterDoiService($doi))->save();

        $this->assertDatabaseHas('articles', ['titre' => 'A Programming Environment for Visual Block-Based Domain-Specific Languages']);
    }


    private static function getDoiArticle(): string
    {
        return '10.1016/j.procs.2015.08.452';
    }

    private static function getBibtexArticle(): string
    {
        return <<<EOT
@article{
  doi = {10.1016/j.procs.2015.08.452},
  url = {https://linkinghub.elsevier.com/retrieve/pii/S1877050915025879},
  author = {Kurihara, Azusa and Sasaki, Akira and Wakita, Ken and Hosobe, Hiroshi},
  title = {A Programming Environment for Visual Block-Based Domain-Specific Languages},
  journal = {Procedia Computer Science},
  volume = {62},
  pages = {287-296},
  publisher = {Elsevier BV},
  year = {2015}
}\n
EOT;
    }
}
