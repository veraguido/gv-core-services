<?php

namespace Tests\Services;

use Gvera\Helpers\config\Config;
use Gvera\Services\TwigService;
use PHPUnit\Framework\TestCase;

class TwigServiceTest extends TestCase
{

    private TwigService $service;

    public function setUp():void
    {
        $this->service = new TwigService(new Config(),  __DIR__ . '/../Views/');
        parent::setUp();
    }

    /**
     * @test
     */
    public function testNeedsTwig()
    {

        $needsTwig = $this->service->needsTwig('Test', 'index');
        $this->assertTrue($needsTwig);
        $this->service->reset();
        $doesntNeedTwig = $this->service->needsTwig('Test', 'asd');
        $this->assertFalse($doesntNeedTwig);
    }

    /**
     * @test
     */
    public function testLoadTwig()
    {
        $env = $this->service->loadTwig();
        $this->assertNotNull($env);
    }

    public function testRender()
    {
        $this->service->reset();
        $this->service->loadTwig();
        $this->assertTrue($this->service->render('Test', 'index', []) === 'asd');
    }
}