<?php
namespace p4it\twig;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extension\SandboxExtension;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;
use Twig\Sandbox\SecurityPolicy;
use Twig\TwigFunction;
use yii\base\Model;

/**
 * Class TwigBuilder
 */
class TwigBuilder extends Model {

    /**
     * @param array $config
     * @return static
     * @throws \yii\base\InvalidConfigException
     */
    public static function create($config = []) {
        $config['class'] = static::class;

        return \Yii::createObject($config);
    }

    /** @var array */
    protected $allowedTags = [];

    /** @var array */
    protected $allowedFilters = [];

    /** @var array */
    protected $allowedMethods = [];

    /** @var array */
    protected $allowedProperties = [];

    /** @var array  */
    protected $allowedFunctions = [];

    /** @var bool */
    protected $sandbox = false;

    /** @var array */
    protected $environmentOptions = [];

    /** @var string|null */
    protected $loaderClass = null;

    /** @var array */
    protected $loaderConstructParams = [];

    /** @var bool */
    protected $debug = false;

    public function build() {
        $policy = new SecurityPolicy($this->allowedTags, $this->allowedFilters, $this->allowedMethods, $this->allowedProperties, $this->allowedFunctions);

        $loader = new $this->loaderClass(...$this->loaderConstructParams);

        $twig = new Environment($loader);

        if ($this->sandbox) {
            $sandbox = new SandboxExtension($policy, true);

            $twig->addExtension($sandbox);
        }

        if ($this->debug) {
            $extension = new DebugExtension;
            $twig->addExtension($extension);
            $twig->enableDebug();
        }

        return $twig;
    }

    public function setSandbox(bool $sandbox) {
        $this->sandbox = $sandbox;

        return $this;
    }

    public function setDebug(bool $debug) {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @param string[] $templates
     *
     * @return $this
     */
    public function setArrayLoader($templates) {
        $this->setLoaderClass(ArrayLoader::class);
        $this->setLoaderConstructParams([$templates]);

        return $this;
    }

    /**
     * @param array $loaders
     *
     * @return $this
     */
    public function setChainLoader($loaders) {
        $this->setLoaderClass(ChainLoader::class);
        $this->setLoaderConstructParams([$loaders]);

        return $this;
    }

    /**
     * @param string[]|string $paths
     * @param string|null $rootPath
     *
     * @return $this
     */
    public function setFileSystemLoader($paths, $rootPath = null) {
        $this->setLoaderClass(FilesystemLoader::class);
        $this->setLoaderConstructParams([$paths, $rootPath]);

        return $this;
    }

    public function setAllowedTags(array $allowedTags) {
        $this->allowedTags = $allowedTags;

        return $this;
    }

    public function setAllowedFilters(array $allowedFilters) {
        $this->allowedFilters = $allowedFilters;

        return $this;
    }

    public function setAllowedMethods(array $allowedMethods) {
        $this->allowedMethods = $allowedMethods;

        return $this;
    }

    public function setAllowedProperties(array $allowedProperties) {
        $this->allowedProperties = $allowedProperties;

        return $this;
    }
    public function setAllowedFunctions(array $allowedFunctions) {
        $this->allowedFunctions = $allowedFunctions;

        return $this;
    }

    public function setEnvironmentOptions(array $environmentOptions) {
        $this->environmentOptions = $environmentOptions;

        return $this;
    }

    public function addEnvironmentOptions(string ...$environmentOptions) {
        foreach ($environmentOptions as $environmentOption) {
            $this->environmentOptions[] = $environmentOption;
        }

        return $this;
    }

    public function setLoaderClass(string $loaderClass) {
        $this->loaderClass = $loaderClass;

        return $this;
    }

    public function setLoaderConstructParams(array $loaderConstructParams) {
        $this->loaderConstructParams = $loaderConstructParams;

        return $this;
    }

    public function addLoaderConstructParams(mixed ...$loaderConstructParams) {
        foreach ($loaderConstructParams as $loaderConstructParam) {
            $this->loaderConstructParams[] = $loaderConstructParam;
        }

        return $this;
    }

}