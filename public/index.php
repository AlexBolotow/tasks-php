<?php

$pid = getmypid(); // Получить ID процесса
echo "Поток: $pid" . PHP_EOL;
//echo "Команда: $command" . PHP_EOL;

$stockFile = fopen('stock.csv', 'r+');
if (!$stockFile) {
    echo "Не удалось открыть файл." . PHP_EOL;
    exit;
}

while (!flock($stockFile, LOCK_EX | LOCK_NB)) {
    echo "Ожидание доступа к файлу..." . PHP_EOL;
    sleep(1);
}

echo "Поток $pid получил блокировку и обрабатывает файл..." . PHP_EOL;

sleep(2);
echo "Обработка завершена." . PHP_EOL;

flock($stockFile, LOCK_UN); // Снять блокировку
fclose($stockFile); // Закрыть файл

?>