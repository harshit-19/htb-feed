<?php

namespace Kawanamiyuu\HtbFeed\Bookmark;

use function GuzzleHttp\Promise\all;

class HtbClient
{
    private const MAX_PAGE = 10;

    /**
     * @var EntryListLoader
     */
    private $entryListLoader;

    /**
     * @var BookmarkExtractor
     */
    private $bookmarkExtractor;

    /**
     * @param EntryListLoader   $entryListLoader
     * @param BookmarkExtractor $bookmarkExtractor
     */
    public function __construct(EntryListLoader $entryListLoader, BookmarkExtractor $bookmarkExtractor)
    {
        $this->entryListLoader = $entryListLoader;
        $this->bookmarkExtractor = $bookmarkExtractor;
    }

    /**
     * @param Category $category
     *
     * @return Bookmarks
     */
    public function fetch(Category $category): Bookmarks
    {
        $loader = $this->entryListLoader;
        $extractor = $this->bookmarkExtractor;

        $promises = [];
        foreach (range(1, self::MAX_PAGE) as $page) {
            $promises[] = $loader($category, $page)->then(function ($html) use($extractor) {
                return $extractor($html)->toArray();
            });
        }

        /* @var Bookmark[][] $results */
        $results = all($promises)->wait();

        $bookmarks = new Bookmarks(array_merge(...$results));

        return $bookmarks->sort(function (Bookmark $a, Bookmark $b) {
            // date DESC
            return $b->date < $a->date ? -1 : 1;
        });
    }
}