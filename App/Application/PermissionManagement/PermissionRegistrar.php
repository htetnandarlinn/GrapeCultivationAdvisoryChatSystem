<?php

namespace App\Application\PermissionManagement;

use App\Domain\PermissionManagement\Repositories\PermissionRepositoryInterface;
use App\Presentation\Attributes\Permission;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

final class PermissionRegistrar
{
    private string $controllersPath;

    public function __construct(
        private PermissionRepositoryInterface $repository,
    ) {
        $this->controllersPath = dirname(__DIR__, 2) . '/Presentation/Controllers';
    }

    public function register(): int
    {
        $registered = 0;

        foreach ($this->findControllerFiles() as $filePath) {
            $className = $this->fileToClassName($filePath);
            if ($className === null) {
                continue;
            }

            try {
                $reflection = new \ReflectionClass($className);
            } catch (\Throwable) {
                continue;
            }

            if ($reflection->isAbstract() || $reflection->isInterface() || $reflection->isTrait()) {
                continue;
            }

            foreach ($reflection->getMethods() as $method) {
                foreach ($method->getAttributes(Permission::class) as $attr) {
                    $perm = $attr->newInstance();
                    if (!$this->repository->existsByKey($perm->key)) {
                        try {
                            $this->repository->create($perm->key, $perm->name, $perm->description);
                            $registered++;
                        } catch (\PDOException) {
                            // Skip duplicate name — a different key may share this name
                        }
                    }
                }
            }
        }

        return $registered;
    }

    private function findControllerFiles(): array
    {
        if (!is_dir($this->controllersPath)) {
            return [];
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->controllersPath)
        );

        $files = [];
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getRealPath();
            }
        }

        return $files;
    }

    private function fileToClassName(string $filePath): ?string
    {
        $basePath = dirname(__DIR__, 2) . '/Presentation/Controllers';
        $relativePath = substr($filePath, strlen(realpath($basePath)) + 1);
        $relativePath = str_replace(['/', '\\'], '\\', $relativePath);
        $relativePath = preg_replace('/\.php$/', '', $relativePath);

        if ($relativePath === '') {
            return null;
        }

        return 'App\\Presentation\\Controllers\\' . $relativePath;
    }
}
