<?php

namespace Gvera\Services;

use Gvera\Helpers\config\Config;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigService
{
    const VIEWS_PREFIX = __DIR__ . '/../Views/';
    private $loadTwig;
    private $twig;

    /**
     * TwigService constructor.
     * @param Config $config
     * @param string|null $viewsPath
     * @param string $cachePath
     */
    public function __construct(
        private Config $config,
        private ?string $viewsPath = null,
        private string $cachePath = __DIR__ . '/../../var/cache/views/'
    ) {
    }

    /**
     * @param $controllerName
     * @param $controllerMethod
     * @return bool
     */
    public function needsTwig($controllerName, $controllerMethod): bool
    {
        $path = $this->viewsPath ?? self::VIEWS_PREFIX;
        if (null === $this->loadTwig) {
            $this->loadTwig = file_exists(
                $path .
                $controllerName .
                DIRECTORY_SEPARATOR .
                $controllerMethod . '.html.twig'
            );
        }
        return $this->loadTwig;
    }

    /**
     * @return Environment
     */
    public function loadTwig(): Environment
    {
        $viewsPath = $this->viewsPath ?? self::VIEWS_PREFIX;
        $devMode = boolval($this->config->getConfigItem('devmode'));
        $cache = $devMode ? false : $this->cachePath;
        $loader = new FilesystemLoader($viewsPath);
        $this->twig = new Environment($loader, ['cache' => $cache, 'debug' => $devMode]);
        return $this->twig;
    }

    /**
     * @param $name
     * @param $method
     * @param $viewParams
     * @return string
     */
    public function render($name, $method, $viewParams): string
    {
        return $this->twig->render(
            DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $method . '.html.twig',
            $viewParams
        );
    }

    public function reset()
    {
        $this->twig = null;
        $this->loadTwig = null;
    }
}
