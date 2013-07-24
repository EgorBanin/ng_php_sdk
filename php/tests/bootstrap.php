<?php

const SRC_PATH = '../src/';
$srcPath = realpath(__DIR__.DIRECTORY_SEPARATOR.SRC_PATH);
set_include_path(get_include_path().PATH_SEPARATOR.$srcPath);