<?php
date_default_timezone_set('America/Sao_Paulo');

$meses = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
$servicos = [
    'Banho e Tosa',
    'Consulta Veterinária',
    'Recreação',
    'Cuidados Especiais'
];

function escapar($texto) {
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

$hoje = date('Y-m-d');
$mes = $_GET['mes'] ?? date('Y-m');

if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes)) {
    $mes = date('Y-m');
}

$primeiroDia = new DateTimeImmutable($mes . '-01');
$mesAnterior = $primeiroDia->modify('-1 month')->format('Y-m');
$proximoMes = $primeiroDia->modify('+1 month')->format('Y-m');
$mesPorExtenso = ucfirst($meses[(int) $primeiroDia->format('n') - 1]) . ' de ' . $primeiroDia->format('Y');
$inicio = (int) $primeiroDia->format('N') - 1;
$diasNoMes = (int) $primeiroDia->format('t');
$totalCasas = (int) ceil(($inicio + $diasNoMes) / 7) * 7;

$arquivo = __DIR__ . '/dados/agendamentos.json';
$agendamentos = [];
if (is_file($arquivo)) {
    $dados = json_decode(file_get_contents($arquivo), true);
    if (is_array($dados)) {
        $agendamentos = $dados;
    }
}

usort($agendamentos, function ($a, $b) {
    return strcmp($a['data'] . $a['horario'], $b['data'] . $b['horario']);
});

$agendamentosPorDia = [];
$proximos = [];
$totalMes = 0;
foreach ($agendamentos as $agendamento) {
    $agendamentosPorDia[$agendamento['data']][] = $agendamento;
    if (substr($agendamento['data'], 0, 7) === $mes) {
        $totalMes++;
    }
    if ($agendamento['data'] >= $hoje) {
        $proximos[] = $agendamento;
    }
}

$mensagens = [
    'sucesso' => 'Agendamento realizado com sucesso!',
    'ocupado' => 'Esse horário já está ocupado. Escolha outro.',
    'invalido' => 'Confira os dados do formulário e tente novamente.',
    'fechado' => 'Os atendimentos acontecem de segunda a sábado, das 8h às 18h.',
    'passado' => 'Não é possível agendar uma data ou horário que já passou.',
    'erro' => 'Não foi possível salvar o agendamento. Confira a permissão da pasta dados.'
];
$status = $_GET['status'] ?? '';
$servicoSelecionado = $_GET['servico'] ?? '';
$dataInicial = $mes === date('Y-m') ? $hoje : $primeiroDia->format('Y-m-d');
if ($dataInicial < $hoje) {
    $dataInicial = $hoje;
}
if ($dataInicial === $hoje && (int) date('G') >= 18) {
    $dataInicial = date('Y-m-d', strtotime($dataInicial . ' +1 day'));
}
if ((int) date('N', strtotime($dataInicial)) === 7) {
    $dataInicial = date('Y-m-d', strtotime($dataInicial . ' +1 day'));
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos | PetVida</title>
    <link rel="stylesheet" href="../../estilo.css">
    <link rel="stylesheet" href="../../responsividade.css">
    <link rel="stylesheet" href="agendamento.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="nav_header">
            <a href="../../index.html"><img class="img_logo" src="../../logo_PetVida.png" alt="PetVida"></a>
            <img class="menu" src="../../ícones/menu.png" alt="Abrir menu" onclick="toggleMenu()">
            <div class="div_menu" id="menu">
                <div class="div_menu_inutil">
                    <ul class="ul_header">
                        <li><a class="link_header" href="../../index.html">Home</a></li>
                        <li><a class="link_header" href="../sobre nós/sobre_nos.html">Sobre nós</a></li>
                        <li><a class="link_header" href="../serviços/servicos.html">Serviços</a></li>
                        <li><a class="link_header" href="../contato/contato.html">Contato</a></li>
                    </ul>
                    <div class="div_header">
                        <a class="button_cta secondary" href="../acesso/acesso.html">Entrar</a>
                        <a class="button_cta primary" href="../acesso/acesso.html">Cadastrar</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="agenda-main">
        <div class="agenda-introducao">
            <div>
                <div class="div_tag"><img src="../../icon_patinha.png" alt=""><p>Agendamento PetVida</p></div>
                <h1>Agenda de atendimentos</h1>
                <p>Escolha um dia no calendário e reserve um horário para seu pet.</p>
            </div>
            <button class="botao-agendar" type="button" id="novoAgendamento">+ Novo agendamento</button>
        </div>

        <?php if (isset($mensagens[$status])): ?>
            <p class="aviso <?= $status === 'sucesso' ? 'aviso-sucesso' : 'aviso-erro' ?>" role="status">
                <?= escapar($mensagens[$status]) ?>
            </p>
        <?php endif; ?>

        <div class="agenda-conteudo">
            <aside class="agenda-lateral">
                <div class="lateral-bloco">
                    <h2>Próximos agendamentos</h2>
                    <?php if (count($proximos) === 0): ?>
                        <p class="sem-agendamentos">Nenhum atendimento marcado por enquanto.</p>
                    <?php else: ?>
                        <?php foreach (array_slice($proximos, 0, 5) as $agendamento): ?>
                            <div class="item-proximo">
                                <span class="data-proximo"><?= escapar(date('d/m', strtotime($agendamento['data']))) ?></span>
                                <div>
                                    <strong><?= escapar($agendamento['pet']) ?></strong>
                                    <span><?= escapar($agendamento['horario']) ?> · <?= escapar($agendamento['servico']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="lateral-bloco lateral-ajuda">
                    <h2>Como agendar?</h2>
                    <p>Selecione um dia, preencha os dados e confirme o horário.</p>
                    <p>Atendimento de segunda a sábado, das 8h às 18h.</p>
                    <p class="nota-ficticio">Projeto demonstrativo. Não são realizados agendamentos reais.</p>
                </div>
            </aside>

            <section class="agenda-calendario" aria-label="Calendário de agendamentos">
                <div class="calendario-topo">
                    <div>
                        <h2><?= escapar($mesPorExtenso) ?></h2>
                        <span><?= $totalMes ?> <?= $totalMes === 1 ? 'agendamento' : 'agendamentos' ?> neste mês</span>
                    </div>
                    <div class="calendario-navegacao">
                        <a href="?mes=<?= escapar($mesAnterior) ?>" aria-label="Mês anterior">&#10094;</a>
                        <a class="botao-hoje" href="agendamento.php">Hoje</a>
                        <a href="?mes=<?= escapar($proximoMes) ?>" aria-label="Próximo mês">&#10095;</a>
                    </div>
                </div>
                <div class="calendario-grade">
                    <?php foreach (['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'] as $diaSemana): ?>
                        <div class="cabecalho-dia"><?= $diaSemana ?></div>
                    <?php endforeach; ?>

                    <?php for ($posicao = 0; $posicao < $totalCasas; $posicao++): ?>
                        <?php
                        $numeroDia = $posicao - $inicio + 1;
                        $data = $primeiroDia->modify(($numeroDia - 1) . ' days')->format('Y-m-d');
                        $dentroDoMes = $numeroDia >= 1 && $numeroDia <= $diasNoMes;
                        $diaFechado = (int) date('N', strtotime($data)) === 7;
                        $podeAgendar = $dentroDoMes && $data >= $hoje && !$diaFechado;
                        $eventos = $agendamentosPorDia[$data] ?? [];
                        ?>
                        <div class="dia-calendario <?= !$dentroDoMes ? 'dia-fora' : '' ?> <?= $data === $hoje ? 'dia-hoje' : '' ?>">
                            <button type="button" class="numero-dia" data-dia="<?= $data ?>" aria-label="Agendar em <?= escapar(date('d/m/Y', strtotime($data))) ?>" <?= !$podeAgendar ? 'disabled' : '' ?>>
                                <?= (int) date('j', strtotime($data)) ?>
                            </button>
                            <?php foreach (array_slice($eventos, 0, 2) as $agendamento): ?>
                                <div class="evento" title="<?= escapar($agendamento['pet'] . ' - ' . $agendamento['servico'] . ' (' . $agendamento['horario'] . ')') ?>">
                                    <strong><?= escapar($agendamento['horario']) ?></strong> <?= escapar($agendamento['pet']) ?>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($eventos) > 2): ?>
                                <span class="mais-eventos">+ <?= count($eventos) - 2 ?> atendimento(s)</span>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
                <p class="dica-calendario">Clique no número de uma data disponível para fazer um agendamento.</p>
            </section>
        </div>
    </main>

    <dialog class="modal-agendamento" id="modalAgendamento" aria-labelledby="tituloModal">
        <div class="modal-topo">
            <div>
                <span>Novo agendamento</span>
                <h2 id="tituloModal">Vamos cuidar do seu pet!</h2>
            </div>
            <button type="button" class="fechar-modal" id="fecharModal" aria-label="Fechar">×</button>
        </div>
        <form action="salvar.php" method="POST" id="formAgendamento">
            <label for="tutor">Nome do tutor</label>
            <input type="text" id="tutor" name="tutor" maxlength="80" placeholder="Seu nome" required>

            <label for="pet">Nome do pet</label>
            <input type="text" id="pet" name="pet" maxlength="60" placeholder="Nome do seu pet" required>

            <label for="servico">Serviço</label>
            <select id="servico" name="servico" required>
                <option value="">Selecione o serviço</option>
                <?php foreach ($servicos as $servico): ?>
                    <option value="<?= escapar($servico) ?>" <?= $servico === $servicoSelecionado ? 'selected' : '' ?>><?= escapar($servico) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="form-linha">
                <div>
                    <label for="data">Data</label>
                    <input type="date" id="data" name="data" min="<?= $hoje ?>" value="<?= escapar($dataInicial) ?>" required>
                </div>
                <div>
                    <label for="horario">Horário</label>
                    <select id="horario" name="horario" required>
                        <option value="">Selecione</option>
                        <?php for ($hora = 8; $hora <= 17; $hora++): ?>
                            <option value="<?= sprintf('%02d:00', $hora) ?>"><?= sprintf('%02d:00', $hora) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <p class="form-observacao">Cada horário comporta um agendamento nesta versão do projeto.</p>
            <div class="modal-botoes">
                <button type="button" class="botao-cancelar" id="cancelarAgendamento">Cancelar</button>
                <button type="submit" class="botao-agendar">Confirmar agendamento</button>
            </div>
        </form>
    </dialog>

    <div class="rodape-agenda">PetVida · Projeto acadêmico de agendamento fictício</div>
    <script src="agendamento.js"></script>
</body>
</html>
