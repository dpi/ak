<?php

declare(strict_types = 1);

namespace dpi\ak;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use dpi\ak\Annotation\AvatarService;
use dpi\ak\AvatarKit\AvatarServices\AvatarServiceInterface;
use dpi\ak\Exception\AvatarDiscoveryException;
use Symfony\Component\Finder\Finder;

/**
 * Discovers avatar services.
 */
class AvatarServiceDiscovery implements AvatarServiceDiscoveryInterface {

  /**
   * Sub-namespace within PSR-4 paths.
   *
   * @var string
   */
  protected $subdir;

  /**
   * Interface to validate avatar services against.
   *
   * @var string
   */
  protected $serviceInterface;

  /**
   * The annotation class.
   *
   * @var string
   */
  protected $annotationClass;

  /**
   * Cache of avatar service classes.
   *
   * @var string[]
   *   An array of classes.
   */
  protected $classes = [];

  /**
   * Cache of avatar service classes.
   *
   * @var \dpi\ak\Annotation\AvatarService[]|null
   *   An array of classes, or NULL if discovery has not yet taken place.
   */
  protected $annotations;

  /**
   * AvatarServiceDiscovery constructor.
   *
   * @param array $root_namespaces
   *   An array of namespaces where keys are namespaces and values are
   *   directories.
   * @param string $subDirectory
   *   Subdirectory within PSR-4 roots to look for plugins.
   * @param string $serviceInterface
   *   Validates services implement this class.
   * @param string $annotationClass
   *   Looks for classes with this annotation.
   */
  public function __construct(array $root_namespaces, string $subDirectory, string $serviceInterface, string $annotationClass) {
    $this->subdir = $subDirectory;
    $this->serviceInterface = $serviceInterface;
    $this->annotationClass = $annotationClass;
    $this->discoverClasses($root_namespaces);
  }

  /**
   * Discover files that look like avatar service classes.
   *
   * @param array $namespaces
   *   An array of namespaces where keys are namespaces and values are
   *   directories.
   */
  protected function discoverClasses(array $namespaces) : void {
    foreach ($namespaces as $namespace => $namespace_directories) {
      foreach ($namespace_directories as $namespace_directory) {
        $finder = $this->createFinder()
          ->files()
          ->name('*.php')
          ->in($namespace_directory)
          ->path($this->subdir);
        foreach ($finder as $file) {
          // Remove the namespace directory from the full file path.
          $sub_path = substr($file->getPath(), strlen($namespace_directory));
          if (!empty($sub_path)) {
            $sub_path = substr($sub_path, 1);
            $sub_path = str_replace('/', '\\', $sub_path);
            $sub_path .= '\\';
            $this->classes[] = $namespace . $sub_path . $file->getBasename('.php');
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getAvatarServices() : array {
    if (!isset($this->annotations)) {
      $this->discoverAnnotations();
    }
    return $this->annotations;
  }

  /**
   * Discovers and caches annotations.
   */
  protected function discoverAnnotations() : void {
    AnnotationRegistry::reset();
    AnnotationRegistry::registerLoader('class_exists');

    $reader = $this->createReader();

    $this->annotations = [];
    foreach ($this->classes as $class) {

//      if (!class_exists($class)) {
//        continue;
//      }

      $reflection = new \ReflectionClass($class);
      if (!$reflection->isSubclassOf($this->serviceInterface)) {
        continue;
      }

      $annotation = $reader->getClassAnnotation($reflection, $this->annotationClass);
      if ($annotation) {
        $this->annotations[$class] = $annotation;
      }
    }

    AnnotationRegistry::reset();
  }

  /**
   * {@inheritdoc}
   */
  public function getMetadata(string $id) : AvatarService {
    foreach ($this->getAvatarServices() as $annotation) {
      if ($id == $annotation->id) {
        return $annotation;
      }
    }
    throw new AvatarDiscoveryException(sprintf('Requested service `%s` does not exist.', $id));
  }

  /**
   * {@inheritdoc}
   */
  public function newInstance(string $id) : AvatarServiceInterface {
    foreach ($this->getAvatarServices() as $class => $annotation) {
      if ($id == $annotation->id) {
        return new $class();
      }
    }
    throw new AvatarDiscoveryException(sprintf('Requested service `%s` does not exist.', $id));
  }

  /**
   * Creates a new reader instance.
   *
   * @return \Doctrine\Common\Annotations\Reader
   *   A reader instance.
   */
  protected function createReader() {
    $reader = new SimpleAnnotationReader();
    // Add the namespace so plugins don't have to use FQN annotation. Only
    // simple reader has this feature.
    $reader->addNamespace('\dpi\ak\Annotation');
    //    $reader = new \Doctrine\Common\Annotations\FileCacheReader(
    //      $reader,
    //      dirname(__DIR__) . '' . DIRECTORY_SEPARATOR . 'cache',
    //      true
    //    );
    return $reader;
  }

  /**
   * Creates a new finder instance.
   *
   * @return \Symfony\Component\Finder\Finder
   *   A finder instance.
   */
  protected function createFinder() {
    return new Finder();
  }

}
