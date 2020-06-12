<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Feed;

use K9u\Enum\AbstractEnum;

/**
 * @method static FeedType HTML()
 * @method static FeedType ATOM()
 * @method static FeedType RSS()
 */
final class FeedType extends AbstractEnum
{
    /**
     * @return array<string, array{string, string}>
     */
    protected static function enumerate(): array
    {
        return [
            'HTML' => [HtmlGenerator::class, 'text/html; charset=UTF-8'],
            'ATOM' => [AtomGenerator::class, 'application/atom+xml; charset=UTF-8'],
            'RSS' => [RssGenerator::class, 'application/rss+xml; charset=UTF-8'],
        ];
    }

    public function generator(): string
    {
        return $this->getConstantValue()[0];
    }

    public function contentType(): string
    {
        return $this->getConstantValue()[1];
    }
}
