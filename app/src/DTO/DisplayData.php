<?php

namespace App\DTO;

class DisplayData
{
    /**
     * @var string
     */
    private $limit;

    /**
     * @var string
     */
    private $offset;

    /**
     * @param string $limit
     * @param string $offset
     */
    public function __construct(string $limit, string $offset)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function getLimit(): string
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getOffset(): string
    {
        return $this->offset;
    }
}
