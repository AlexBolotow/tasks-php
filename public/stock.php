<?php
/*
const ROW_NOT_FIND = -1;
const STATE_STOCK = "Stock";
const STATE_HOLD = "Hold";
const STATE_SOLD = "Sold";

const ACTION_HOLD = "--hold";
const ACTION_CONFIRM = "--confirm";

function readCsv($file): array
{
    $data = [];
    while (($row = fgetcsv($file)) !== false) {
        $data[] = $row;
    }

    return $data;
}

function findIndexRowBySku($data, $sku): int
{
    foreach ($data as $index => $row) {
        if ($row[0] == $sku) {
            return $index;
        }
    }

    return ROW_NOT_FIND;
}

function findIndexRowBySkuAndPriceWithStateStock($data, $sku, $price): int
{
    foreach ($data as $index => $row) {
        if ($row[0] == $sku && $price == $row[1] && $row[2] == STATE_STOCK) {
            return $index;
        }
    }

    return ROW_NOT_FIND;
}

function findAllIndexRowsBySkuAndLessPriceWithStateStock($data, $sku, $price): array
{
    $indexRows = [];
    foreach ($data as $index => $row) {
        if (str_contains($row[0], $sku) && $price >= $row[1] && $row[2] == STATE_STOCK) {
            $indexRows[] = $index;
        }
    }

    return $indexRows;
}

function findAllIndexRowsByOrder($data, $order): array
{
    $indexRows = [];
    foreach ($data as $index => $row) {
        if (str_contains($row[2], $order)) {
            $indexRows[] = $index;
        }
    }

    return $indexRows;
}

function holdAction(&$stockData, $stockHeaders, $order): void
{

    $indexRowWithEqualPrice = findIndexRowBySkuAndPriceWithStateStock($stockData, $stockHeaders['sku'],
        $stockHeaders['price']);
    if ($indexRowWithEqualPrice != ROW_NOT_FIND) {
        updateRowForHold($indexRowWithEqualPrice, $stockData, $stockHeaders, $order);
        return;
    }

    $indexRowsWithLessPrice = findAllIndexRowsBySkuAndLessPriceWithStateStock($stockData, $stockHeaders['sku'],
        $stockHeaders['price']);
    if (!empty($indexRowsWithLessPrice)) {
        foreach ($indexRowsWithLessPrice as $indexRow) {
            if ($stockHeaders['state'] == STATE_HOLD &&
                $stockHeaders['price'] >= $stockData[$indexRow][1]) {

                updateRowForHold($indexRow, $stockData, $stockHeaders, $order);
                break;
            }
        }
    } else {
        echo 'Не удалось выполнить действие' . PHP_EOL;
    }
}

function confirmAction(&$stockData, $stockHeadersToUpdate, $order): void
{
    $indexRows = findAllIndexRowsByOrder($stockData, $order);
    if (!empty($indexRows)) {
        foreach ($indexRows as $indexRow) {
            $stockData[$indexRow][2] = $stockHeadersToUpdate['state'];
        }
    }
}

function updateRowForHold($indexRow, &$stockData, $stockHeadersToUpdate, $order): void
{
    $stockData[$indexRow][2] = $stockHeadersToUpdate['state'] . '/ORDER' . $order;
    if (isset($stockHeadersToUpdate['price'])) {
        $stockData[$indexRow][1] = $stockHeadersToUpdate['price'];
    }

    echo 'После изменений:' . PHP_EOL;
    printStockCsv($stockData);
}

function doAction(&$stockData, $stockHeadersToUpdate, $order, $action): void
{
    if ($action == ACTION_HOLD) {
        holdAction($stockData, $stockHeadersToUpdate, $order);
    }

    if ($action == ACTION_CONFIRM) {
        confirmAction($stockData, $stockHeadersToUpdate, $order);
    }
}

function rewriteCsv($file, $data): void
{
    foreach ($data as $row) {
        fputcsv($file, $row);
    }
}

function parseConsoleArguments($argv, &$stockHeaders, &$order, &$action): void
{
    $action = $argv[1];

    if ($action == ACTION_CONFIRM) {
        $stockHeaders['state'] = STATE_SOLD;
        $order = $argv[2];
    }

    if ($action == ACTION_HOLD) {
        $stockHeaders['sku'] = $argv[2];
        $stockHeaders['state'] = STATE_HOLD;
        $order = $argv[6];
        if (isset($argv[4])) {
            $stockHeaders['price'] = $argv[4];
        }
    }
}

function printStockCsv($stockData): void
{
    foreach ($stockData as $row) {
        foreach ($row as $value) {
            echo $value . ',';
        }
        echo PHP_EOL;
    }
}

$stockHeaders = [
    'sku' => null,
    'price' => null,
    'state' => null
];
$order = null;
$action = null;
parseConsoleArguments($_SERVER['argv'], $stockHeaders, $order, $action);
$command = implode(' ', array_slice($argv, 1));

$stockFile = fopen('stock.csv', 'r+');
if (!$stockFile) {
    echo "Не удалось открыть файл." . PHP_EOL;
    exit;
}

while (!flock($stockFile, LOCK_EX | LOCK_NB)) {
    echo "Ожидание доступа к файлу..." . PHP_EOL;
    sleep(1);
}


echo "Команда: $command" . PHP_EOL;

$stockData = readCsv($stockFile);

echo 'До изменений:' . PHP_EOL;
printStockCsv($stockData);

doAction($stockData, $stockHeaders, $order, $action);

rewind($stockFile);
ftruncate($stockFile, 0);
rewriteCsv($stockFile, $stockData);

echo PHP_EOL;

flock($stockFile, LOCK_UN);
fclose($stockFile);*/



