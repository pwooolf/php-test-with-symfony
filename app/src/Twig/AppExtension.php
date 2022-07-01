<?php


namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('truncate', [$this, 'truncateText']),
        ];
    }

    public function truncateText(string $text): string
    {
        return sprintf('%s...', mb_substr($text, 0, 200));
    }


}