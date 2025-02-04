<?php
namespace yii\di;

use Psr\Container\ContainerInterface;
use yii\di\contracts\Definition;
use yii\di\exceptions\InvalidConfigException;

/**
 * Class Reference allows us to define a dependency to a service in the container in another service definition.
 * For example:
 * ```php
 * [
 *    InterfaceA::class => ConcreteA::class,
 *    'alternativeForA' => ConcreteB::class,
 *    Service1::class => [
 *        '__construct()' => [
 *            Reference::to('alternativeForA')
 *        ]
 *    ]
 * ]
 * ```
 */
class Reference implements Definition
{
    private $id;

    private function __construct($id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public static function to(string $id): Reference
    {
        return new self($id);
    }

    public function resolve(ContainerInterface $container, array $params = [])
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection passing parameters for containers supporting them */
        return $container->get($this->id, $params);
    }

    /**
     * Restores class state after using `var_export()`.
     *
     * @param array $state
     * @return self
     * @throws InvalidConfigException when $state property does not contain `id` parameter
     * @see var_export()
     */
    public static function __set_state($state)
    {
        if (!isset($state['id'])) {
            throw new InvalidConfigException(
                'Failed to instantiate class "Reference". Required parameter "id" is missing'
            );
        }

        return new self($state['id']);
    }
}
