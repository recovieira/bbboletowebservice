# **Cliente WebService para registro de boletos no Banco do Brasil**
A partir de 2017, a rede bancária brasileira trás uma nova plataforma de geração Boletos de Cobrança Registrada, buscando uma maior agilidade e segurança para toda sociedade.

Conforme divulgado pela [Febraban](https://portal.febraban.org.br/pagina/3150/1094/pt-br/servicos-novo-plataforma-boletos), o início da implantação deverá ocorrer em julho/2017, devendo ser totalmente concluída até dezembro/2017.

A solução desenvolvida pelo Banco do Brasil é baseada em Web Services e utiliza o protocolo OAuth 2.0 para autenticação e autorização das requisições.

O código-fonte proposto foi desenvolvido em PHP segundo a especificação para consumir o Web Service fornecido pelo Banco do Brasil.

### Índice
- [Começando...](#comecando)
  - [Pré-requisitos](#prerequisitos)
  - [Exemplo](#exemplo)
- [Estrutura de arquivos e pastas](#estrutura)
- [Changelog](#changelog)
- [Autor](#autor)
- [Licença](#licenca)


### Começando...
Você precisa ter as seguintes bibliotecas do PHP instaladas para usar esta implementação.

#### Pré-requisitos
  - Curl (php-curl)
  - Json (php-json)
  - XML (php-xml)

#### Exemplo
Baixe primeiramente a implementação:

```sh
$ git clone https://github.com/recovieira/bbboletowebservice.git minhaimplementacao
$ cd minhaimplementacao
```

Primeiramente, você deve modificar o arquivo "index.php" para atender às suas necessidades.

Posteriormente, execute o exemplo contido seja pela Web ou pela CLI (você precisará ter o pacote "php-cli" instalado). Para executar pela linha de comando, dê o comando:

```sh
$ php index.php
```

### Estrutura de arquivos e pastas
Estrutura breve do conteúdo:

```
├── index.php			# contém um exemplo simples de uso
├── Nova Cobrança - Manual de Integração v1.4.pdf		# especificação do Banco do Brasil
└── lib					# pasta contendo a classe
    └── bb.php			# arquivo contendo a implementação da requisição do serviço
```

### Changelog
#### V 1.0.0
Lançamento inicial.

### Autor
Reginaldo Coimbra Vieira (recovieira@gmail.com)

### Licença
O **Cliente WebService para registro de boletos no Banco do Brasil** é licenciado sob a Licença MIT (MIT). Você pode usar, copiar, modificar, integrar, publicar, distribuir e/ou vender cópias dos produtos finais, mas deve sempre declarar que Reginaldo Coimbra Vieira (recovieira@gmail.com) é o autor original destes códigos e atribuir um link para https://github.com/recovieira/bbboletowebservice.git.
