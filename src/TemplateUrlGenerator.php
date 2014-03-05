<?php

namespace MPScholten\GitHubApi;

class TemplateUrlGenerator
{
    public static function generate($template, $data)
    {
        $url = $template;

        foreach ($data as $name => $value) {
            $url = str_replace('{/' . $name . '}', $value, $url);
        }

        return $url;
    }
}
