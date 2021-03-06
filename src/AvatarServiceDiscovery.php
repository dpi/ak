<?php

declare(strict_types = 1);

namespace dpi\ak;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use dpi\ak\Annotation\AvatarService;
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
   * Cache of ID to class names.
   *
   * @var array|null
   *   An array of classes keyed by annotation ID.
   */
  protected $idMap = NULL;

  /**
   * Cache of avatar service classes.
   *
   * @var \dpi\ak\Annotation\AvatarService[]
   *   An array of avatar services keyed by class name.
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
    $classes = $this->discoverClasses($root_namespaces);
    $this->annotations = $this->discoverAnnotations($classes);
  }

  /**
   * Discover files that look like avatar service classes.
   *
   * @param array $namespaces
   *   An array of namespaces where keys are namespaces and values are
   *   directories.
   *
   * @return string[]
   *   An array of classes.
   */
  protected function discoverClasses(array $namespaces) : array {
    $classes = [];

    foreach ($namespaces as $namespace => $namespace_directories) {
      $namespace_directories = array_filter($namespace_directories, function ($dir) {
        return is_dir($dir);
      });
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
            $classes[] = $namespace . $sub_path . $file->getBasename('.php');
          }
        }
      }
    }

    return $classes;
  }

  /**
   * {@inheritdoc}
   */
  public function getAvatarServices() : array {
    return $this->annotations;
  }

  /**
   * Discovers and caches annotations.
   *
   * @param string[] $classes
   *   An array of classes.
   *
   * @return \dpi\ak\Annotation\AvatarService[]
   *   An array of avatar services keyed by class name.
   */
  protected function discoverAnnotations(array $classes) : array {
    AnnotationRegistry::reset();
    AnnotationRegistry::registerLoader('class_exists');

    $reader = $this->createReader();

    $annotations = [];
    foreach ($classes as $class) {
      $reflection = new \ReflectionClass($class);
      if (!$reflection->isSubclassOf($this->serviceInterface)) {
        continue;
      }

      /** @var \dpi\ak\Annotation\AvatarService $annotation */
      $annotation = $reader->getClassAnnotation($reflection, $this->annotationClass);
      if ($annotation) {
        $annotations[$class] = $annotation;
      }
    }

    AnnotationRegistry::reset();

    return $annotations;
  }

  /**
   * {@inheritdoc}
   */
  public function getMetadata(string $id) : AvatarService {
    $class = $this->getClass($id);
    $avatar_services = $this->getAvatarServices();
    if (isset($avatar_services[$class])) {
      return $avatar_services[$class];
    }
    throw new AvatarDiscoveryException(sprintf('Requested service `%s` does not exist.', $id));
  }

  /**
   * {@inheritdoc}
   */
  public function getClass(string $id) : string {
    if (!isset($this->idMap)) {
      $services = $this->getAvatarServices();
      $this->idMap = array_combine(
        array_map(function (AvatarService $service) : string {
          return $service->id;
        }, $services),
        array_keys($services)
      );
    }

    $class = $this->idMap[$id] ?? NULL;
    if ($class) {
      return $class;
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
