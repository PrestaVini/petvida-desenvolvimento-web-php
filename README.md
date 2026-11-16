# PetVida — Desenvolvimento Web com PHP

## Sobre o projeto

O **PetVida** é um projeto acadêmico de Desenvolvimento de Sistemas Web que simula o site de um pet shop. A aplicação reúne páginas institucionais, apresentação de serviços, formulário de contato e um sistema fictício de agendamento desenvolvido em PHP.

## Objetivo da atividade

Aplicar HTML, CSS, JavaScript e PHP na construção de uma aplicação web organizada e responsiva, integrando a interface executada no navegador com o processamento realizado no servidor.

## Funcionalidades atuais

- Página inicial responsiva;
- Apresentação dos serviços oferecidos;
- Páginas “Sobre nós”, “Contato” e “Trabalhe conosco”;
- Formulário de contato processado em PHP;
- Calendário de agendamentos com navegação entre meses;
- Cadastro de tutor, pet, serviço, data e horário;
- Exibição dos agendamentos no calendário;
- Lista dos próximos atendimentos;
- Bloqueio de datas passadas e domingos;
- Validação para impedir dois agendamentos no mesmo dia e horário;
- Armazenamento local dos registros em arquivo JSON.

## Sistema de agendamento

A versão atual possui um sistema acadêmico de agendamento localizado em:

```text
PetShop-main/paginação/agendamento/
```

Os registros são armazenados em `dados/agendamentos.json`. Portanto, a pasta `dados` precisa permitir escrita pelo PHP/Apache.

Esta é uma versão parcial. A edição e o cancelamento de agendamentos, o cadastro de usuários, o banco de dados MySQL e a área administrativa ainda poderão ser implementados nas próximas etapas.

## Tecnologias utilizadas

- **HTML5** — estrutura das páginas;
- **CSS3** — estilização e responsividade;
- **JavaScript** — interações no navegador;
- **PHP** — processamento e validação dos agendamentos;
- **JSON** — armazenamento temporário dos registros;
- **VS Code** — desenvolvimento do projeto;
- **XAMPP/Apache** — execução local com suporte a PHP.

## Estrutura principal

```text
PetShop-main/
├── index.html
├── app.js
├── estilo.css
├── responsividade.css
├── paginação/
│   ├── acesso/
│   ├── agendamento/
│   │   ├── dados/
│   │   │   └── agendamentos.json
│   │   ├── agendamento.php
│   │   ├── agendamento.css
│   │   ├── agendamento.js
│   │   └── salvar.php
│   ├── contato/
│   ├── serviços/
│   └── sobre nós/
├── imagens index/
└── ícones/
```

## Como executar com XAMPP

1. Instale e abra o XAMPP;
2. Copie a pasta `PetShop-main` para a pasta `htdocs`;
3. Inicie o serviço **Apache**;
4. Acesse:

```text
http://localhost/PetShop-main/
```

5. Clique em **Agendar agora** para abrir o calendário.

Também é possível acessar diretamente:

```text
http://localhost/PetShop-main/paginação/agendamento/agendamento.php
```

Os arquivos PHP não devem ser abertos com duplo clique, pois precisam ser processados por um servidor.

## Como executar com o servidor integrado do PHP

Na pasta que contém `PetShop-main`, execute:

```bash
php -S localhost:8000
```

Depois, acesse:

```text
http://localhost:8000/PetShop-main/
```

> O servidor integrado do PHP não aplica as regras do arquivo `.htaccess`. Utilize somente dados fictícios durante os testes locais.

## Observação

Este projeto foi desenvolvido para fins acadêmicos. O sistema de agendamento é demonstrativo e não realiza reservas reais.

## Autor

Desenvolvido por [PrestaVini](https://github.com/PrestaVini).
