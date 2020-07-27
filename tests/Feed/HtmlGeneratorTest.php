<?php

declare(strict_types=1);

namespace Kawanamiyuu\HtbFeed\Feed;

use DateTime;
use DateTimeZone;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmark;
use Kawanamiyuu\HtbFeed\Bookmark\Bookmarks;
use Kawanamiyuu\HtbFeed\Bookmark\Category;
use Kawanamiyuu\HtbFeed\Bookmark\Users;
use PHPUnit\Framework\TestCase;

class HtmlGeneratorTest extends TestCase
{
    public function testInvoke()
    {
        $meta = new FeedMeta(
            'http://example.com?category=it&users=10',
            'http://example.com/atom?category=it&users=10',
            'http://example.com/rss?category=it&users=10',
            new DateTime('2020-06-02T09:00:00+09:00', new DateTimeZone('Asia/Tokyo'))
        );

        $bookmark = new Bookmark();
        $bookmark->category = Category::IT();
        $bookmark->users = Users::valueOf(10);
        $bookmark->title = 'entry title';
        $bookmark->url = 'http://entry.example.com';
        $bookmark->domain = 'entry.example.com';
        $bookmark->date = new DateTime('2020-06-01T12:00+09:00', new DateTimeZone('Asia/Tokyo'));

        $feed = (new HtmlGenerator())($meta, new Bookmarks([$bookmark]));

        $expected = <<<FEED
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>はてなブックマークの新着エントリー</title>
    <link rel="alternate" type="application/atom+xml" href="http://example.com/atom?category=it&users=10" title="はてなブックマークの新着エントリー" />
    <link rel="alternate" type="application/rss+xml" href="http://example.com/rss?category=it&users=10" title="はてなブックマークの新着エントリー" />
</head>
<body>

<ul>
        <li>
        <a href="http://entry.example.com" target="_blank">
            entry title        </a>
    </li>
    </ul>

</body>
</html>
FEED;

        $this->assertSame(trim($expected), trim($feed->content()));
        $this->assertSame('text/html', $feed->contentType());
    }
}
