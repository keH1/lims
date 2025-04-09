#!/usr/bin/env node

/**
 * Скрипт установки хука для Node
 */

const { execSync } = require('child_process');
const { resolve, join } = require('path');
const { existsSync, mkdirSync, copyFileSync } = require('fs');
const os = require('os');

const scriptDir = __dirname;
const ulabDir = resolve(scriptDir, '..');

try {
    process.chdir(ulabDir);
    
    const gitDir = execSync('git rev-parse --git-dir', { encoding: 'utf8' }).trim();
    const hooksDir = resolve(gitDir, 'hooks');
    
    if (!existsSync(hooksDir)) {
        mkdirSync(hooksDir, { recursive: true });
    }
    
    const isWindows = os.platform() === 'win32';
    
    const phpSource = join(scriptDir, 'commit-msg.php');
    const targetFile = join(hooksDir, 'commit-msg');
    copyFileSync(phpSource, targetFile);
    
    if (!isWindows) {
        execSync(`chmod +x "${targetFile}"`);
    }
    
    console.log('Git-хук успешно установлен');
} catch (error) {
    console.error('Ошибка при установке Git-хука: ', error.message);
    process.exit(1);
}