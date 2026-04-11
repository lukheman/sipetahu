<?php

/**
 * ============================================================
 *  Universal Installer Script
 *  Compatible with Linux & Windows
 * ============================================================
 */

define('MIN_PHP_VERSION', '8.2.0');
define('REPO_URL', 'https://github.com/lukheman/.git');
define('REPO_DIR', '');

// ============================================================
//  Helper: Deteksi OS
// ============================================================
function isWindows(): bool
{
    return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
}

// ============================================================
//  Helper: Warna / Styling CLI
// ============================================================
function color(string $text, string $type = 'default'): string
{
    if (isWindows()) {
        return $text;
    }

    $colors = [
        'default'  => "\033[0m",
        'red'      => "\033[0;31m",
        'green'    => "\033[0;32m",
        'yellow'   => "\033[1;33m",
        'blue'     => "\033[0;34m",
        'cyan'     => "\033[0;36m",
        'white'    => "\033[1;37m",
        'bold'     => "\033[1m",
        'dim'      => "\033[2m",
    ];

    $reset = "\033[0m";
    $code  = $colors[$type] ?? $reset;

    return "{$code}{$text}{$reset}";
}

function printLine(string $text = ''): void
{
    echo $text . PHP_EOL;
}

function printHeader(): void
{
    $line = str_repeat('═', 60);
    printLine(color($line, 'cyan'));
    printLine(color('  ██ ███    ██ ███████ ████████  █████  ██      ██      ', 'cyan'));
    printLine(color('  ██ ████   ██ ██         ██    ██   ██ ██      ██      ', 'cyan'));
    printLine(color('  ██ ██ ██  ██ ███████    ██    ███████ ██      ██      ', 'cyan'));
    printLine(color('  ██ ██  ██ ██      ██    ██    ██   ██ ██      ██      ', 'cyan'));
    printLine(color('  ██ ██   ████ ███████    ██    ██   ██ ███████ ███████ ', 'cyan'));
    printLine(color($line, 'cyan'));
    printLine(color('  Universal Installer  •  Linux & Windows Compatible', 'dim'));
    printLine(color($line, 'cyan'));
    printLine();
}

function printStep(int $step, int $total, string $label): void
{
    $badge = color(" STEP {$step}/{$total} ", 'blue');
    printLine();
    printLine($badge . ' ' . color($label, 'bold'));
    printLine(color(str_repeat('─', 50), 'dim'));
}

function printSuccess(string $msg): void
{
    printLine(color('  ✔  ', 'green') . $msg);
}

function printWarning(string $msg): void
{
    printLine(color('  ⚠  ', 'yellow') . $msg);
}

function printError(string $msg): void
{
    printLine(color('  ✖  ', 'red') . $msg);
}

function printInfo(string $msg): void
{
    printLine(color('  ➜  ', 'cyan') . $msg);
}

function printOutput(string $output): void
{
    $lines = explode("\n", trim($output));
    foreach ($lines as $line) {
        if (trim($line) !== '') {
            printLine(color('     │ ', 'dim') . $line);
        }
    }
}

// ============================================================
//  Helper: Jalankan perintah
// ============================================================
function run(string $cmd): bool
{
    $proc = proc_open($cmd, [STDIN, STDOUT, STDERR], $pipes);
    if (! is_resource($proc)) {
        return false;
    }
    $code = proc_close($proc);

    return $code === 0;
}

// ============================================================
//  Helper: Ubah direktori
// ============================================================
function changeDir(string $dir): bool
{
    if (!is_dir($dir)) {
        return false;
    }
    return chdir($dir);
}

// ============================================================
//  Langkah 1 – Cek Dependensi
// ============================================================
function checkDependencies(): bool
{
    printStep(1, 4, 'Memeriksa Dependensi');

    $allOk = true;

    // --- Cek PHP Version ---
    $phpVersion = PHP_VERSION;
    if (version_compare($phpVersion, MIN_PHP_VERSION, '>=')) {
        printSuccess("PHP {$phpVersion} terdeteksi (minimum: " . MIN_PHP_VERSION . ")");
    } else {
        printError("PHP {$phpVersion} tidak memenuhi syarat minimum " . MIN_PHP_VERSION);
        printWarning("Silakan upgrade PHP ke versi " . MIN_PHP_VERSION . " atau lebih baru.");
        $allOk = false;
    }

    // --- Cek Git ---
    $check = isWindows() ? 'where git' : 'command -v git';
    if (run($check)) {
        printSuccess("Git terdeteksi.");
    } else {
        printError('Git tidak ditemukan.');
        printWarning(isWindows()
            ? 'Install Git dari https://git-scm.com/download/win'
            : 'Jalankan: sudo apt install git  atau  sudo yum install git');
        $allOk = false;
    }

    // --- Cek Composer ---
    $check = isWindows() ? 'where composer' : 'command -v composer';
    if (run($check)) {
        printSuccess("Composer terdeteksi.");
    } else {
        printError('Composer tidak ditemukan.');
        printWarning('Install Composer dari https://getcomposer.org/download/');
        $allOk = false;
    }

    if (!$allOk) {
        printLine();
        printError('Beberapa dependensi belum terpenuhi. Instalasi dihentikan.');
    }

    return $allOk;
}

// ============================================================
//  Langkah 2 – Git Clone
// ============================================================
function gitClone(): bool
{
    printStep(2, 4, 'Mengunduh Repository');

    $repoUrl = REPO_URL;
    $repoDir = REPO_DIR;

    if (is_dir($repoDir)) {
        printWarning("Folder '{$repoDir}' sudah ada.");
        printInfo("Melewati proses clone, menggunakan folder yang sudah ada.");
        return true;
    }

    printInfo("Clone dari: {$repoUrl}");
    printInfo("Tujuan    : ./{$repoDir}");
    printLine();

    if (run("git clone {$repoUrl} {$repoDir}")) {
        printSuccess('Repository berhasil di-clone.');
        return true;
    }

    printError('Gagal melakukan git clone.');
    return false;
}

// ============================================================
//  Langkah 3 – Masuk ke Direktori Repository
// ============================================================
function enterDirectory(): bool
{
    printStep(3, 4, 'Masuk ke Direktori Repository');

    $repoDir = REPO_DIR;

    if (!is_dir($repoDir)) {
        printError("Direktori '{$repoDir}' tidak ditemukan.");
        return false;
    }

    if (changeDir($repoDir)) {
        printSuccess("Berhasil masuk ke: " . getcwd());
        return true;
    }

    printError("Gagal masuk ke direktori '{$repoDir}'.");
    return false;
}

// ============================================================
//  Langkah 4 – Jalankan setup.php
// ============================================================
function runSetup(): bool
{
    printStep(4, 4, 'Menjalankan setup.php');

    if (!file_exists('setup.php')) {
        printError("File setup.php tidak ditemukan di: " . getcwd());
        return false;
    }

    printInfo("Menjalankan: php setup.php");
    printLine();

    if (run('php setup.php')) {
        printSuccess('setup.php selesai dijalankan.');
        return true;
    }

    printError('setup.php gagal dijalankan.');
    return false;
}

// ============================================================
//  Ringkasan Akhir
// ============================================================
function printSummary(array $steps): void
{
    $line = str_repeat('═', 60);
    printLine();
    printLine(color($line, 'cyan'));
    printLine(color('  RINGKASAN INSTALASI', 'bold'));
    printLine(color($line, 'cyan'));

    $allSuccess = true;
    foreach ($steps as $name => $success) {
        if ($success) {
            printLine(color('  ✔', 'green') . "  {$name}");
        } else {
            printLine(color('  ✖', 'red') . "  {$name}");
            $allSuccess = false;
        }
    }

    printLine(color($line, 'cyan'));

    if ($allSuccess) {
        printLine(color('  🎉  Instalasi berhasil diselesaikan!', 'green'));
    } else {
        printLine(color('  ⚠   Instalasi selesai dengan beberapa error.', 'yellow'));
        printLine(color('       Periksa pesan error di atas untuk detail.', 'dim'));
    }

    printLine(color($line, 'cyan'));
    printLine();

    // --- Developer Info ---
    $line2 = str_repeat('─', 60);
    printLine(color($line2, 'dim'));
    printLine(color('  Developer', 'bold'));
    printLine(color($line2, 'dim'));
    printLine(color('  Nama      : ', 'dim') . color('Akmal', 'white'));
    printLine(color('  Instagram : ', 'dim') . color('@lukheeman', 'cyan'));
    printLine(color('  No. HP    : ', 'dim') . color('082250212121', 'white'));
    printLine(color('  Portfolio : ', 'dim') . color('https://lukheman.github.io/portfolio/', 'cyan'));
    printLine(color('  Aplikasita: ', 'dim') . color('https://aplikasita.my.id', 'cyan'));
    printLine(color($line2, 'dim'));
    printLine();
}

// ============================================================
//  MAIN
// ============================================================
function main(): void
{
    printHeader();

    printLine(color('  OS Terdeteksi : ', 'dim') . color(isWindows() ? 'Windows' : 'Linux/Unix', 'white'));
    printLine(color('  PHP Binary    : ', 'dim') . color(PHP_BINARY, 'white'));
    printLine(color('  Working Dir   : ', 'dim') . color(getcwd(), 'white'));
    printLine();

    $results = [];

    // Step 1
    $results['Cek Dependensi'] = checkDependencies();
    if (!$results['Cek Dependensi']) {
        printSummary($results);
        exit(1);
    }

    // Step 2
    $results['Git Clone'] = gitClone();
    if (!$results['Git Clone']) {
        printSummary($results);
        exit(1);
    }

    // Step 3
    $results['Masuk Direktori'] = enterDirectory();
    if (!$results['Masuk Direktori']) {
        printSummary($results);
        exit(1);
    }

    // Step 4
    $results['Jalankan setup.php'] = runSetup();

    printSummary($results);
    exit($results['Jalankan setup.php'] ? 0 : 1);
}

main();
