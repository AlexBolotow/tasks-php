<?php

class StockService
{
    public const int ROW_NOT_FIND = -1;
    public const string STATE_STOCK = "Stock";
    public const string STATE_HOLD = "Hold";
    public const string STATE_SOLD = "Sold";
    public const string ACTION_HOLD = "--hold";
    public const string ACTION_CONFIRM = "--confirm";

    private static array $stockHeaders = ['sku', 'price', 'state'];

    private $stockFile;
    private array $stockData;

    public function __construct(string $stockFileName)
    {
        $this->stockFile = fopen('stock.csv', 'r+');
        if (!$this->stockFile) {
            echo "Не удалось открыть файл." . PHP_EOL;
        }
    }

    public function doAction(string $action, array $stockHeaders, string $order): void
    {
        $this->lockFile();
        $this->readCsv();

        if ($action == self::ACTION_HOLD) {
            $this->holdAction($stockHeaders, $order);
        }

        if ($action == self::ACTION_CONFIRM) {
            $this->confirmAction($stockHeaders, $order);
        }

        $this->rewriteCsv();
        $this->unlockFile();
    }

    private function holdAction(array $stockHeaders, string $order): void
    {
        if ($stockHeaders['state'] == self::STATE_HOLD) {
            $rowIndex = $this->findRowIndexBySkuAndPriceWithStateStock($stockHeaders['sku'],
                $stockHeaders['price']);
            if ($rowIndex != self::ROW_NOT_FIND) {
                $this->updateRowForHold($rowIndex, $stockHeaders, $order);
                return;
            }

            $rowIndexes = $this->findAllRowIndexesBySkuAndLessPriceWithStateStock($stockHeaders['sku'],
                $stockHeaders['price']);
            if (!empty($rowIndexes)) {
                $this->updateRowForHold($rowIndexes[0], $stockHeaders, $order);
            } else {
                echo 'Не нашлось подходящего товара' . PHP_EOL;
            }
        } else {
            echo 'Не удалось выполнить действие' . PHP_EOL;
        }
    }

    private function confirmAction(array $stockHeaders, string $order): void
    {
        if ($stockHeaders['state'] == self::STATE_SOLD) {
            $rowIndexes = $this->findAllRowIndexesByOrder($order);
            if (!empty($rowIndexes)) {
                foreach ($rowIndexes as $rowIndex) {
                    $this->updateRowForConfirm($rowIndex, $stockHeaders);
                }
            } else {
                echo 'Не нашлось подходящего заказа';
            }
        } else {
            echo 'Не удалось выполнить действие' . PHP_EOL;
        }
    }

    private function updateRowForHold(int $rowIndex, array $stockHeadersToUpdate, string $order): void
    {
        $this->stockData[$rowIndex]['state'] = $stockHeadersToUpdate['state'] . '/ORDER' . $order;
        $this->stockData[$rowIndex]['price'] = $stockHeadersToUpdate['price'];

        echo 'После изменений:' . PHP_EOL;
        $this->printCsv();
    }

    private function updateRowForConfirm(int $rowIndex, array $stockHeadersToUpdate): void
    {
        $this->stockData[$rowIndex]['state'] = $stockHeadersToUpdate['state'];

        echo 'После изменений:' . PHP_EOL;
        $this->printCsv();
    }

    private function findRowIndexBySkuAndPriceWithStateStock(string $sku, int $price): int
    {
        foreach ($this->stockData as $index => $row) {
            if ($row['sku'] == $sku && $row['price'] == $price && $row['state'] == self::STATE_STOCK) {
                return $index;
            }
        }

        return self::ROW_NOT_FIND;
    }

    private function findAllRowIndexesBySkuAndLessPriceWithStateStock(string $sku, string $price): array
    {
        $rowIndexes = [];
        foreach ($this->stockData as $index => $row) {
            if (str_contains($row['sku'], $sku) && $row['price'] < $price && $row['state'] == self::STATE_STOCK) {
                $rowIndexes[] = $index;
            }
        }

        return $rowIndexes;
    }

    private function findAllRowIndexesByOrder(string $order): array
    {
        $rowIndexes = [];
        foreach ($this->stockData as $index => $row) {
            if (str_contains($row['state'], $order)) {
                $rowIndexes [] = $index;
            }
        }

        return $rowIndexes;
    }

    private function readCsv(): void
    {
        while (($row = fgetcsv($this->stockFile)) !== false) {
            $this->stockData[] = array_combine(self::$stockHeaders, $row);
        }
    }

    private function rewriteCsv(): void
    {
        rewind($this->stockFile);
        ftruncate($this->stockFile, 0);
        foreach ($this->stockData as $row) {
            fputcsv($this->stockFile, $row);
        }
    }

    private function printCsv(): void
    {
        foreach ($this->stockData as $row) {
            foreach ($row as $value) {
                echo $value . ',';
            }
            echo PHP_EOL;
        }
    }

    private function lockFile(): void
    {
        while (!flock($this->stockFile, LOCK_EX | LOCK_NB)) {
            echo "Ожидание доступа к файлу..." . PHP_EOL;
            sleep(1);
        }
    }

    private function unlockFile(): void
    {
        flock($this->stockFile, LOCK_UN);
    }

    public function __destruct()
    {
        fclose($this->stockFile);
    }
}