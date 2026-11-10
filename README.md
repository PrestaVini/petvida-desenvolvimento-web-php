# PetVida — Projeto de Desenvolvimento de Sistemas Web

## Sobre o projeto

O **PetVida** é um projeto acadêmico da disciplina de **Desenvolvimento de Sistemas Web**. A atividade consiste em utilizar **PHP em um site**, aplicando a linguagem no desenvolvimento de funcionalidades para uma plataforma fictícia de Pet Shop.

O site apresenta informações sobre serviços para animais, páginas institucionais, área de acesso e formulário de contato. A proposta é evoluir a estrutura inicialmente criada com HTML, CSS e JavaScript, acrescentando processamento no servidor com PHP.

## Objetivo da atividade

O objetivo principal é praticar o desenvolvimento de aplicações web que utilizem PHP, integrando páginas visuais com funcionalidades executadas no servidor.

Entre as possibilidades de desenvolvimento do projeto estão:

- Processamento de formulários com PHP;
- Sistema de login e cadastro;
- Agendamento de serviços;
- Cadastro de clientes e animais;
- Uso de sessões;
- Integração com banco de dados.

## Situação atual

O projeto está **em desenvolvimento**. A versão presente neste repositório contém a estrutura visual do PetVida e uma implementação inicial em PHP para o formulário de contato. Outras funcionalidades em PHP serão adicionadas durante a atividade.

## Tecnologias utilizadas

- **PHP** — processamento das funcionalidades no servidor;
- **HTML5** — estrutura das páginas;
- **CSS3** — aparência e responsividade;
- **JavaScript** — interações no navegador;
- **VS Code** — edição dos arquivos do projeto.

## Estrutura principal

```text
PetVida/
├── index.html
├── app.js
├── estilo.css
├── responsividade.css
├── paginação/
│   ├── acesso/
│   ├── contato/
│   │   └── form.php
│   ├── serviços/
│   └── sobre nós/
├── imagens index/
└── ícones/
```

## Como executar

Para que os arquivos PHP funcionem, o projeto deve ser executado por um servidor com suporte a PHP.

### Usando o servidor integrado do PHP

1. Instale o PHP no computador.
2. Abra a pasta do projeto no VS Code.
3. Abra o terminal na raiz do projeto.
4. Execute:

```bash
php -S localhost:8000
```

5. Acesse no navegador:

```text
http://localhost:8000
```

### Usando o XAMPP

1. Instale e abra o XAMPP.
2. Copie a pasta do projeto para a pasta `htdocs`.
3. Inicie o serviço Apache.
4. Acesse o projeto pelo endereço correspondente em `localhost`.

## Observação

Este projeto foi criado para fins acadêmicos e representa um Pet Shop fictício. As funcionalidades serão desenvolvidas e atualizadas ao longo da disciplina.
