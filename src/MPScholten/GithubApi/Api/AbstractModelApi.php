<?php


namespace MPScholten\GithubApi\Api;


use Guzzle\Http\ClientInterface;

abstract class AbstractModelApi extends AbstractApi implements PopulateableInterface
{
    private $attributeStorage = [];
    private $loadedMap = [];

    public function __construct(ClientInterface $client = null)
    {
        parent::__construct($client);
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
        foreach ($data as $name => $value) {
            $this->loadedMap[$name] = true;
            $this->attributeStorage[$name] = $value;
        }
    }

    protected function isAttributeLoaded($attribute)
    {
        return array_key_exists($attribute, $this->loadedMap) && $this->loadedMap[$attribute];
    }

    protected function getAttribute($attribute)
    {
        if (!$this->isAttributeLoaded($attribute)) {
            $this->load();
        }

        return $this->attributeStorage[$attribute];
    }
}
