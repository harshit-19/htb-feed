<?php

namespace Kawanamiyuu\HtbFeed\Bookmark;

final class Bookmarks implements \IteratorAggregate
{
    /**
     * @var Bookmark[]
     */
    private $bookmarks;

    /**
     * @param Bookmark[] $bookmarks
     */
    public function __construct(array $bookmarks)
    {
        $this->bookmarks = $bookmarks;
    }

    /**
     * @param callable $callback
     *
     * @return Bookmarks
     */
    public function filter(callable $callback): Bookmarks
    {
        $bookmarks = array_filter($this->bookmarks, $callback);
        // reset index
        $bookmarks = array_values($bookmarks);

        return new self($bookmarks);
    }

    /**
     * @param callable $callback
     *
     * @return Bookmarks
     */
    public function sort(callable $callback): Bookmarks
    {
        $bookmarks = $this->bookmarks;
        usort($bookmarks, $callback);

        return new self($bookmarks);
    }

    /**
     * @return Bookmark[]
     */
    public function toArray(): array
    {
        return $this->bookmarks;
    }
    
    /**
     * @return \Traversable|Bookmark[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->bookmarks);
    }
}