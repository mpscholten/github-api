<?php


namespace MPScholten\GithubApi\Api;


use Guzzle\Http\ClientInterface;
use MPScholten\GithubApi\Utils;

abstract class AbstractModelApi extends AbstractApi implements PopulateableInterface
{
    private $attributeStorage = [];
    private $loadedMap = [];

    public function __construct(ClientInterface $client = null)
    {
        parent::__construct($client);

        if (!property_exists($this, 'attributes')) {
            throw new \ErrorException(sprintf(
                'Please define %s::$attributes for using %s',
                get_class($this),
                __CLASS__
            ));
        }
    }


    /**
     * Fully loads the model
     */
    abstract protected function load();

    /**
     * @see getModelAttributes()
     */
    public function populate(array $data)
    {
        foreach ($this->attributes as $attribute) {
            if (Utils::flatArrayKeyExists($data, $attribute)) {
                $this->attributeStorage[$attribute] = Utils::flatArrayGet($data, $attribute);
                $this->loadedMap[$attribute] = true;
            }
        }
    }

    private function isLoaded($attribute)
    {
        return array_key_exists($attribute, $this->loadedMap) && $this->loadedMap[$attribute];
    }

    protected function getAttribute($attribute)
    {
        if (!in_array($attribute, $this->attributes, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid attribute "%s", expected one of "%s"',
                $attribute,
                implode(', ', $this->attributes)
            ));
        }

        if (!$this->isLoaded($attribute)) {
            $this->load();
        }

        return $this->attributeStorage[$attribute];
    }
}
