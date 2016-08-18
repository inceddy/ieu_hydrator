# ieu\Hydrator
Simple PHP hydration/extraction concept. Inspired by the work of [Zend/Hydator](https://github.com/zendframework/zend-hydrator).

# Example

```php
use ieu\Hydrator\ClosureHydrator;
use ieu\Hydrator\NamingStrategies\UnderscoreNamingStrategy;


// Setup
$namingStrategy = new UnderscoreNamingStrategy;
$hydrator = (new ClosureHydrator)
	->setNamingStrategy(namingStrategy);

// Usage
class Dummy {
	protected $aName;
	protected $bName;
}

$dummy = $hydrator->hydrate(new Dummy, ['aName' => 1, 'bName' => 2]);

$data = $hydrator->extract($dummy); // Returns ['a_name' => 1, 'b_name' => 2]
```