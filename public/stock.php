<?php

const ROW_NOT_FIND = -1;
const STATE_STOCK = "Stock";
const STATE_HOLD = "Hold";
const STATE_SOLD = "Sold";

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


function updateRow(&$data, $sku, $priceToUpdate, $stateToUpdate): void
{
    $indexRow = findIndexRowBySku($data, $sku);
    if ($indexRow != ROW_NOT_FIND) {
        $currentState = $data[$indexRow][2];
        if ($currentState == STATE_STOCK && $stateToUpdate == STATE_HOLD) {
            $data[$indexRow][2] = $stateToUpdate;
            $data[$indexRow][1] = $priceToUpdate;
            echo "bla";
        }

        if ($stateToUpdate == STATE_HOLD && $stateToUpdate == STATE_SOLD) {
            $data[$indexRow][2] = $stateToUpdate;
            $data[$indexRow][0] = $priceToUpdate;
        }
    }
}

function rewriteCsv($file, $data): void
{
    foreach ($data as $row) {
        fputcsv($file, $row);
    }
}

function parseConsoleArguments($argv, &$sku, &$price, &$state): void
{
    $state = ucfirst(substr($argv[1], 2));
    $sku = $argv[2];
    if ($argv[1] == '--hold' && $argv[3] == '--price') {
        $price = $argv[4];
    }
}


//php stock.php --hold TKU100 --price 100
//php stock.php --confirm ORDER100

//параллельный запуск
//php stock.php --hold TKU100 --price 100 & php stock.php --hold TKU200 --price 200 & php stock.php --hold TKU300 --price 300

/*$sku = null;
$price = null;
$state = null;
parseConsoleArguments($_SERVER['argv'], $sku, $price, $state);*/
var_dump($_SERVER['argv']);
/*$stockFile = fopen('stock.csv', 'r+');
if (flock($stockFile, LOCK_EX)) {
    $data = readCsv($stockFile);
    //print_r($data);
    //fclose($stockFile);

    updateRow($data, $sku, $price, $state);

    rewind($stockFile); // Переместить указатель в начало файла
    ftruncate($stockFile, 0); // Очистить файл
    rewriteCsv($stockFile, $data);

    flock($stockFile, LOCK_UN);
}

fclose($stockFile);*/



