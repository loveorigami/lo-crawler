<?php

namespace modules\feed\components\youtube\dto;

use modules\feed\components\youtube\dto\item\VideoItemDto;
use yii\helpers\ArrayHelper;

/**
 * Class VideoListDto
 *
 * @package modules\feed\components\youtube\dto
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
        $res = ArrayHelper::getValue($data, 'results');

        $this->total = ArrayHelper::getValue($res, 'pageInfo.totalResults');
        $items = ArrayHelper::getValue($res, 'items');
        $this->nextPageToken = ArrayHelper::getValue($res, 'nextPageToken');
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
}
