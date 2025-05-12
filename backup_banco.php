<?php
// === CONFIGURAÇÕES ===
$host = 'localhost';
$user = 'root';
$password = ''; // coloque sua senha se houver
$database = 'sala_informatica'; // troque pelo nome real do seu banco
$data = date('Y-m-d_H-i-s');
$diretorioBackup = 'C:\\laragon\\www\\SistemaJFSOrganizado\\backups';

if (!is_dir($diretorioBackup)) {
    mkdir($diretorioBackup, 0777, true);
}

$arquivoSQL = "$diretorioBackup\\backup_{$database}_$data.sql";
$arquivoZIP = "$arquivoSQL.zip";

// === COMANDO mysqldump ===
$comando = "C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe --user=$user --password=$password --host=$host $database > \"$arquivoSQL\"";

// Executa o backup
exec($comando, $output, $result);

// Verifica se o backup foi gerado com sucesso
if ($result === 0 && file_exists($arquivoSQL)) {
    // Compacta o arquivo em .zip
    $zip = new ZipArchive();
    if ($zip->open($arquivoZIP, ZipArchive::CREATE) === TRUE) {
        $zip->addFile($arquivoSQL, basename($arquivoSQL));
        $zip->close();
        unlink($arquivoSQL); // Remove o .sql após compactar
        echo "✅ Backup realizado com sucesso: $arquivoZIP\n";
    } else {
        echo "❌ Erro ao criar o arquivo ZIP.\n";
    }
} else {
    echo "❌ Erro ao gerar o backup do banco de dados.\n";
}

// === LIMPEZA AUTOMÁTICA DE BACKUPS ANTIGOS (mais de 7 dias) ===
$arquivos = glob("$diretorioBackup\\*.zip");
foreach ($arquivos as $arquivo) {
    if (filemtime($arquivo) < time() - 7 * 24 * 60 * 60) {
        unlink($arquivo);
        echo "🗑️ Backup antigo deletado: $arquivo\n";
    }
}
?>
