#!/usr/bin/env php
<?php
// Скрипт для проверки сообщения коммита

if (!isset($argv[1])) {
    echo "Ошибка: не указан файл сообщения коммита\n";
    exit(1);
}

$commit_msg_file = $argv[1];
$commit_source = isset($argv[2]) ? $argv[2] : '';

if (!file_exists($commit_msg_file)) {
    echo "Ошибка: файл сообщения коммита не найден: $commit_msg_file\n";
    exit(1);
}

$commit_msg = trim(file_get_contents($commit_msg_file));

// Для merge пропускаем проверку
if (empty($commit_msg) || preg_match('/^#/', $commit_msg) || 
    $commit_source === 'merge' || $commit_source === 'squash' ||
    preg_match('/^Merge /', $commit_msg)) {
    exit(0);
}

$commit_types = ["feat", "fix", "bug", "refactor", "docs", "build", "ci", "style", "test", "revert"];

$parts = explode(" ", $commit_msg, 2);
if (count($parts) < 2) {
    echo "Ошибка: сообщение коммита имеет неверный формат.\n";
    echo "Сообщение должно быть в формате: '{префикс}/{проект-номер задачи} {понятное описание}'\n";
    exit(1);
}

$prefix_part = $parts[0];
$description = $parts[1];

$prefix_parts = explode("/", $prefix_part, 2);
if (count($prefix_parts) < 2) {
    echo "Ошибка: неверный формат префикса. Префикс должен быть в формате '{префикс}/{проект-номер задачи}'.\n";
    exit(1);
}

$type = $prefix_parts[0];
$jira_id = $prefix_parts[1];

if (!in_array($type, $commit_types)) {
    echo "Ошибка: недопустимый тип коммита '$type'.\n";
    echo "Допустимые типы: " . implode(", ", $commit_types) . "\n";
    exit(1);
}

if (!preg_match('/^[[:alpha:]]+-[[:digit:]]+$/', $jira_id)) {
    echo "Ошибка: неверный формат идентификатора задачи '$jira_id'.\n";
    echo "Идентификатор должен быть в формате 'ПРОЕКТ-НОМЕР ЗАДАЧИ' (например, LOKI-123).\n";
    exit(1);
}

function utf8_strlen($str) {
    if (empty($str)) {
        return 0;
    }
    
    return preg_match_all('/\X/u', $str);
}

$desc_length = utf8_strlen($description);
if ($desc_length < 10) {
    echo "Ошибка: описание коммита слишком короткое.\n";
    echo "Пожалуйста, добавьте более подробное описание (минимум 10 символов).\n";
    exit(1);
}

exit(0); 