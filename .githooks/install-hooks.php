#!/usr/bin/env php

<?php
/**
 * Скрипт установки хука для директории /ulab
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
    $sourceFile = $isWindows ? $scriptDir . '/commit-msg.bat' : $scriptDir . '/commit-msg';
    $targetFile = $hooksDir . '/commit-msg';
    
    $phpSource = $scriptDir . '/commit-msg.php';
    $phpTarget = $hooksDir . '/commit-msg.php';
    copy($phpSource, $phpTarget);
    
    copy($sourceFile, $targetFile);
    
    if (!$isWindows) {
        chmod($targetFile, 0755);
        chmod($phpTarget, 0755);
    }
    
    echo "Git-хук успешно установлен!\n";
} catch (Exception $e) {
    echo "Ошибка при установке Git-хука: " . $e->getMessage() . "\n";
    exit(1);
}