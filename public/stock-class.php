<?php

include './StockService.php';

function parseConsoleArguments($argv, &$stockHeaders, &$order, &$action): void
{
    $action = $argv[1];

    if ($action == StockService::ACTION_CONFIRM) {
        $stockHeaders['state'] = StockService::STATE_SOLD;
        $order = $argv[2];
    }

    if ($action == StockService::ACTION_HOLD) {
        $stockHeaders['sku'] = $argv[2];
        $stockHeaders['state'] = StockService::STATE_HOLD;
        $order = $argv[6];
        if (isset($argv[4])) {
            $stockHeaders['price'] = $argv[4];
        }
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

$fileName = 'stock.csv';
$stockService = new StockService($fileName);

$stockService->doAction($action,  $stockHeaders,  $order);