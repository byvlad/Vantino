<?php
/**
 * Created by PhpStorm.
 * User: yavlados
 * Date: 10.11.2017
 * Time: 10:55
 */

namespace AppBundle\Twig;


class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'hashtagify',
                [$this, 'hashtagFilter'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function hashtagFilter($text)
    {
        $text = strip_tags($text);

        return preg_replace('/#(\w+)/', ' <a href="/search/hashtag/$1">#$1</a>', $text);
    }
}