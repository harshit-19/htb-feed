<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Feed;

use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use Laminas\Feed\Writer\Feed as LaminasFeed;

class RssGenerator implements FeedGeneratorInterface
{
    public function __invoke(FeedMeta $meta, Bookmarks $bookmarks): Feed
    {
        // RSS 2.0 の仕様
        // http://www.futomi.com/lecture/japanese/rss20.html#hrelementsOfLtitemgt

        $feed = new LaminasFeed();
        $feed->setTitle($meta->title());
        $feed->setFeedLink($meta->rssUrl(), 'rss');
        $feed->setLink($meta->htmlUrl());
        $feed->setDescription($meta->title());
        // channel:pubDate (optional)
        $feed->setDateModified($meta->publishedAt());

        foreach ($bookmarks as $bookmark) {
            $entry = $feed->createEntry();
            $entry->setTitle($bookmark->title);
            $entry->setLink($bookmark->url);
            // item:pubDate (optional)
            $entry->setDateModified($bookmark->date);
            // item:description (optional)
            $entry->setDescription(sprintf(
                'ブクマ数: %s、カテゴリー: %s、発行元: %s',
                $bookmark->users->value(),
                $bookmark->category->label(),
                $bookmark->domain
            ));
            $feed->addEntry($entry);
        }

        $xml = $feed->export('rss');

        // NOTE: DOMElement の仕様で、"&" が自動的に
        //       HTML エスケープ ("&" -> "&amp;") されてしまうので "&" に戻す
        $xml = str_replace('&amp;', '&', $xml);

        return new Feed($xml, 'application/rss+xml');
    }
}
