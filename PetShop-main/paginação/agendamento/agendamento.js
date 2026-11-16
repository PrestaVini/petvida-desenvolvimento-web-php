const modal = document.getElementById('modalAgendamento');
const campoData = document.getElementById('data');

function abrirAgendamento(data) {
    if (data) {
        campoData.value = data;
    }
    modal.showModal();
}

function fecharAgendamento() {
    modal.close();
}

document.getElementById('novoAgendamento').addEventListener('click', function () {
    abrirAgendamento();
});

document.querySelectorAll('[data-dia]').forEach(function (botao) {
    botao.addEventListener('click', function () {
        abrirAgendamento(botao.dataset.dia);
    });
});

document.getElementById('fecharModal').addEventListener('click', fecharAgendamento);
document.getElementById('cancelarAgendamento').addEventListener('click', fecharAgendamento);

modal.addEventListener('click', function (evento) {
    if (evento.target === modal) {
        fecharAgendamento();
    }
});

function toggleMenu() {
    document.getElementById('menu').classList.toggle('menu_open');
}
