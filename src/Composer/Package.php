<?php

declare(strict_types=1);

namespace Roave\YouAreUsingItWrong\Composer;

/** @internal this class is only for supporting internal usage of locker data */
final class Package
{
    private const THIS_PACKAGE_NAME = 'roave/enforce-type-checks';

    /** @var string */
    private $name;

    /** @var PackageAutoload */
    private $autoload;

    /** @var string[] */
    private $dependencies;

    private function __construct(string $name, PackageAutoload $autoload, string ...$dependencies)
    {
        $this->name         = $name;
        $this->autoload     = $autoload;
        $this->dependencies = $dependencies;
    }

    /**
     * @param mixed[] $packageDefinition
     *
     * @psalm-param array{
     *   name: string,
     *   require: null|array<string, string>,
     *   autoload: null|array{
     *    psr-4: null|array<string, string|array<int, string>>,
     *    psr-0: null|array<string, string|array<int, string>>
     *   }
     *  } $packageDefinition
     */
    public static function fromPackageDefinition(array $packageDefinition, string $installationPath) : self
    {
        return new self(
            $packageDefinition['name'],
            PackageAutoload::fromAutoloadDefinition($packageDefinition['autoload'] ?? [], $installationPath),
            ...array_keys($packageDefinition['require'] ?? [])
        );
    }

    public function name() : string
    {
        return $this->name;
    }

    public function autoload() : PackageAutoload
    {
        return $this->autoload;
    }

    public function requiresStrictChecks() : bool
    {
        return in_array(self::THIS_PACKAGE_NAME, $this->dependencies, true);
    }
}
