<?php

namespace App\Service\NewsProvider;

use App\Service\CreateNewsFactory;
use SimpleXMLElement;

class RbcProvider implements ProviderInterface
{
    private const PROVIDER = 'rbc';
    private const URL = 'http://static.feed.rbc.ru/rbc/logical/footer/news.rss';
    private const LIMIT = 15;
    private SimpleXMLElement $data;

    private function getRowNews(): void
    {
        $result = file_get_contents(self::URL, false,
            stream_context_create([
                'http' => [
                    'user_agent' => 'php'
                ]]));

        $this->data = $this->preparedData($result);
    }

    public function getNews(array $allNewsGuid): array
    {
        $this->getRowNews();

        $news = [];
        for ($i = 0; $i < self::LIMIT; $i++) {
            $item = CreateNewsFactory::create($this->data->channel->item[$i], $allNewsGuid, self::PROVIDER);
            if ($item !== null) {
                $news[] = $item;
            }
        }
        return $news;
    }

    private function preparedData($result)
    {
        $prepareResult = preg_replace("/rbc_news:/", '', $result);
        return simplexml_load_string($prepareResult);
    }

}