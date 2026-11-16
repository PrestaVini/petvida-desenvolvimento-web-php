<?php
date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: agendamento.php', true, 303);
    exit;
}

$tutor = is_string($_POST['tutor'] ?? null) ? trim($_POST['tutor']) : '';
$pet = is_string($_POST['pet'] ?? null) ? trim($_POST['pet']) : '';
$servico = is_string($_POST['servico'] ?? null) ? $_POST['servico'] : '';
$data = is_string($_POST['data'] ?? null) ? $_POST['data'] : '';
$horario = is_string($_POST['horario'] ?? null) ? $_POST['horario'] : '';
$mes = preg_match('/^\d{4}-(0[1-9]|1[0-2])-\d{2}$/', $data) ? substr($data, 0, 7) : date('Y-m');

function voltar($status, $mes) {
    header('Location: agendamento.php?mes=' . $mes . '&status=' . $status, true, 303);
    exit;
}

$servicos = ['Banho e Tosa', 'Consulta Veterinária', 'Recreação', 'Cuidados Especiais'];
$horarios = [];
for ($hora = 8; $hora <= 17; $hora++) {
    $horarios[] = sprintf('%02d:00', $hora);
}

$dataValida = DateTimeImmutable::createFromFormat('!Y-m-d', $data);
if (
    strlen($tutor) < 3 || strlen($tutor) > 80 ||
    strlen($pet) < 2 || strlen($pet) > 60 ||
    !in_array($servico, $servicos, true) ||
    !in_array($horario, $horarios, true) ||
    !$dataValida || $dataValida->format('Y-m-d') !== $data
) {
    voltar('invalido', $mes);
}

if ($data . ' ' . $horario <= date('Y-m-d H:i')) {
    voltar('passado', $mes);
}

if ((int) $dataValida->format('N') === 7) {
    voltar('fechado', $mes);
}

$pasta = __DIR__ . '/dados';
$arquivo = $pasta . '/agendamentos.json';
$controle = @fopen($pasta . '/agenda.lock', 'c');
if (!$controle || !flock($controle, LOCK_EX)) {
    voltar('erro', $mes);
}

$conteudo = @file_get_contents($arquivo);
$agendamentos = json_decode($conteudo === false ? '' : $conteudo, true);
if (!is_array($agendamentos)) {
    flock($controle, LOCK_UN);
    fclose($controle);
    voltar('erro', $mes);
}

foreach ($agendamentos as $agendamento) {
    if ($agendamento['data'] === $data && $agendamento['horario'] === $horario) {
        flock($controle, LOCK_UN);
        fclose($controle);
        voltar('ocupado', $mes);
    }
}

$agendamentos[] = [
    'id' => uniqid('agd_'),
    'tutor' => $tutor,
    'pet' => $pet,
    'servico' => $servico,
    'data' => $data,
    'horario' => $horario
];

$salvou = @file_put_contents($arquivo, json_encode($agendamentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
flock($controle, LOCK_UN);
fclose($controle);

voltar($salvou === false ? 'erro' : 'sucesso', $mes);
