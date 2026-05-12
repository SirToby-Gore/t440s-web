#!/usr/bin/env bash

php src/php/index.php > index.html

if [[ $# < 1 ]]; then
       	echo "too few args provided, requires 1 arg page size to convert a html to pdf"
	exit 1       
fi

wkhtmltopdf --page-size "$1" index.html cv.pdf

