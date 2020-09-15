<?php

namespace Tests\Unit\Services;

use App\Services\ImporterDoiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

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
     * test mauvais doi
     *
     * @return void
     */
    public function testMauvaisDoi()
    {
        $this->expectException(InvalidArgumentException::class);
        $doi = 'nimp';
        $bibtex = (new ImporterDoiService($doi))->getBibtex();

        $this->assertEquals('The resource you are looking for doesn\'t exist.', $bibtex);
        $this->assertDatabaseMissing('articles', ['titre' => 'A Programming Environment for Visual Block-Based Domain-Specific Languages']);
    }

    /**
     * test mauvais doi
     *
     * @return void
     */
    public function testMauvaisDoiSave()
    {
        $this->expectException(InvalidArgumentException::class);

        $doi = 'nimp';
        $isSaved = (new ImporterDoiService($doi))->save();

        $this->assertTrue($isSaved);
    }

    /**
     * test mauvais doi
     *
     * @return void
     */
    public function testDoiAvecUrl()
    {
        $doi = 'https://doi.org/10.1504/IJMSO.2014.065444';
        (new ImporterDoiService($doi))->save();

        $this->assertDatabaseHas('articles', ['titre' => 'A domain-specific language for Virtual Classrooms']);
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

        $this->assertStringContainsString(str_replace('\\n', '', static::getBibtexArticle()), $service->getBibtex());
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

    /**
     * test de la création d'un objet ImporterBibtexService
     *
     * @return void
     */
    public function testGetBibtexDoiMajuscules()
    {
        $doi = '10.1504/IJMSO.2014.065444';
        (new ImporterDoiService($doi))->save();

        $this->assertDatabaseHas('articles', ['titre' => 'A domain-specific language for Virtual Classrooms']);
    }



    private static function getDoiArticle(): string
    {
        return '10.1016/j.procs.2015.08.452';
    }

    private static function getBibtexArticle(): string
    {
        return <<<EOT
@article{https://doi.org/10.1016/j.procs.2015.08.452,
  doi = {10.1016/j.procs.2015.08.452},
  url = {https://linkinghub.elsevier.com/retrieve/pii/S1877050915025879},
  author = {Kurihara, Azusa and Sasaki, Akira and Wakita, Ken and Hosobe, Hiroshi},
  title = {A Programming Environment for Visual Block-Based Domain-Specific Languages},
  journal = {Procedia Computer Science},
  volume = {62},
  pages = {287-296},
  publisher = {Elsevier BV},
  year = {2015}
}
EOT;
    }
}
