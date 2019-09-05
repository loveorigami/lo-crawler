<?php

namespace Lo\Crawler\Api\Youtube\Response;

use Lo\Crawler\Api\Youtube\Response\Item\VideoItemDto;
use yii\helpers\ArrayHelper;

/**
 * Class VideoListDto
 *
 * @package Lo\Crawler\Api\Youtube\Response
 */
class VideoListDto
{
    protected $total;
    protected $nextPageToken;
    protected $pageTokens;
    protected $items;

    protected $maxDate = 0;

    public function populate($data): self
    {
        $this->total = ArrayHelper::getValue($data, 'pageInfo.totalResults');
        $items = ArrayHelper::getValue($data, 'items');
        $this->nextPageToken = ArrayHelper::getValue($data, 'nextPageToken');
        $this->pageTokens[] = $this->nextPageToken;

        $this->loadItems($items);

        // echo $this->count . ' - ' . \count($this->items) . ' - ' . $this->total . ' - ' . $this->nextPageToken . PHP_EOL;

        return $this;
    }

    protected function loadItems(array $data): void
    {
        foreach ($data as $item) {
            $dto = new VideoItemDto($item);
            $this->items[] = $dto;
            $this->setMaxDate($dto->getCreatedAt());
        }
    }

    protected function setMaxDate(int $ts): void
    {
        if ($ts > $this->maxDate) {
            $this->maxDate = $ts;
        }
    }

    public function getMaxDate(): ?int
    {
        return $this->maxDate;
    }

    /**
     * @return VideoItemDto[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return \count($this->items);
    }

    public function getNextToken(): ?string
    {
        return $this->nextPageToken;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
