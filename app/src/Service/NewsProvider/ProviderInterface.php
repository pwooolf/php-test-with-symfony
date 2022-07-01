<?php

namespace App\Service\NewsProvider;

interface ProviderInterface
{
    public function getRowNews(): void;
    public function getNews(array $allNewsGuid): array;
}