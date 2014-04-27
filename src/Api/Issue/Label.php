<?php

namespace MPScholten\GitHubApi\Api\Issue;

use MPScholten\GitHubApi\Api\AbstractModelApi;

class Label extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    protected function load()
    {
        $url = $this->getAttribute('url');
        $this->populate($this->get($url));
    }

    /**
     * @return string E.g. "Enhancement"
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * @return string The color of this label, e.g. "84b6eb"
     */
    public function getColor()
    {
        return $this->getAttribute('color');
    }
}
