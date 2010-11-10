#!/bin/bash

# Number of TODOs in the code
echo "-----------------------------------------------------------------"
totalTodoNumber=0
totalFiles=0
for f in $(ls *.php */*.php)
do
    todoNumber=$(cat $f | grep TODO | wc -l)
    if [ $todoNumber -gt 0 ]
    then
        echo "$f has $todoNumber TODO(s)"
        totalTodoNumber=$(($todoNumber + $totalTodoNumber))
        totalFiles=$(($totalFiles+1))
    fi
done
echo
echo "Total: $totalTodoNumber TODO(s) in $totalFiles file(s)"
echo "-----------------------------------------------------------------"
echo

# PhpUnit with coverage
phpunit --coverage-html ./report test/

# CodeSniffer
phpcs --standard=PEAR *.php */*.php