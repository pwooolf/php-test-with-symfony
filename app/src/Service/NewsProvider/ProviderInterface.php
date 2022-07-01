<?php

namespace App\Service\NewsProvider;

interface ProviderInterface
{
    public function getNews(array $allNewsGuid): array;
}