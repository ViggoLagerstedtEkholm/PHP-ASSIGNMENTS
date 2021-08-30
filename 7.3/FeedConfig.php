<?php

/**
 * Feed config class.
 * @author Viggo Lagertedt Ekholm
 */
class FeedConfig
{
    private int $ID;
    private string $URL;
    private int $display_limit;

    /**
     * @return int
     */
    public function getID(): int
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     */
    public function setID(int $ID): void
    {
        $this->ID = $ID;
    }

    /**
     * @return string
     */
    public function getURL(): string
    {
        return $this->URL;
    }

    /**
     * @param string $URL
     */
    public function setURL(string $URL): void
    {
        $this->URL = $URL;
    }

    /**
     * @return int
     */
    public function getDisplayLimit(): int
    {
        return $this->display_limit;
    }

    /**
     * @param int $display_limit
     */
    public function setDisplayLimit(int $display_limit): void
    {
        $this->display_limit = $display_limit;
    }
}