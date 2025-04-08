#!/usr/bin/env node

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
    const sourceFile = isWindows ? join(scriptDir, 'commit-msg.bat') : join(scriptDir, 'commit-msg');
    const targetFile = join(hooksDir, 'commit-msg');
    
    const phpSource = join(scriptDir, 'commit-msg.php');
    const phpTarget = join(hooksDir, 'commit-msg.php');
    copyFileSync(phpSource, phpTarget);
    console.log(`commit-msg.php скопирован в ${phpTarget}`);
    
    copyFileSync(sourceFile, targetFile);
    console.log(`Хук-обертка скопирована в ${targetFile}`);
    
    if (!isWindows) {
        execSync(`chmod +x "${targetFile}"`);
        execSync(`chmod +x "${phpTarget}"`);
        console.log('Делаем файлы исполняемыми');
    }
    
    console.log('Git-хук успешно установлен');
} catch (error) {
    console.error('Ошибка при установке Git-хука: ', error.message);
    process.exit(1);
}