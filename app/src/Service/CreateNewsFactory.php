<?php

namespace App\Service;

use App\Entity\News;
use SimpleXMLElement;

class CreateNewsFactory
{
    public static function create(SimpleXMLElement $item, array $guids = [], string $provider): ?News
    {
        $data = json_decode(json_encode((array)$item), TRUE);
        $guids = array_column($guids, 'guid');

        if (!in_array($data['guid'], $guids)) {

            $news = new News();
            $news->setTitle($data['title']);
            $news->setText($data['full-text']);
            $news->setGuid($data['guid']);
            $news->setProvider($provider);
            $news->setImage(self::getImage($data));
            return $news;
        }

        return null;
    }

    private static function getImage(array $data): ?string
    {
        if (!isset($data['image'])) {
            return null;
        }

        $image = $data['image'];

        if (empty($image)) {
            return null;
        }

        if (!isset($image['url'])) {
            $image = $image[0];
        }

        if (isset($image['url'])) {
            return $image['url'];
        }

        return null;
    }
}