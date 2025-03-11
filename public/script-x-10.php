<?php

$command = "parallel -j 10 php stock.php --hold TKU{} --price 100 --order 100 ::: {1..10} > log.txt 2>&1";
exec($command);



