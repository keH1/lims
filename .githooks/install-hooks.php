#!/usr/bin/env php

<?php
/**
 * Скрипт установки хука для php
 */

$scriptDir = __DIR__;
$ulabDir = dirname($scriptDir);

try {
    chdir($ulabDir);
    
    $gitDir = trim(shell_exec('git rev-parse --git-dir'));
    $hooksDir = $gitDir . '/hooks';
    
    if (!is_dir($hooksDir)) {
        mkdir($hooksDir, 0755, true);
    }
    
    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    
    $phpSource = $scriptDir . '/commit-msg.php';
    $targetFile = $hooksDir . '/commit-msg';
    copy($phpSource, $targetFile);
    
    if (!$isWindows) {
        chmod($targetFile, 0755);
    }
    
    echo "Git-хук успешно установлен!\n";
} catch (Exception $e) {
    echo "Ошибка при установке Git-хука: " . $e->getMessage() . "\n";
    exit(1);
}