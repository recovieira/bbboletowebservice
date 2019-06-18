# **Cliente WebService para registro de boletos no Banco do Brasil**
A partir de 2017, a rede bancária brasileira traz uma nova plataforma de geração Boletos de Cobrança Registrada, buscando uma maior agilidade e segurança para toda sociedade.

Conforme divulgado pela [Febraban](https://portal.febraban.org.br/pagina/3150/1094/pt-br/servicos-novo-plataforma-boletos), a implantação deve ser totalmente concluída a partir de dezembro/2017.

A solução desenvolvida pelo Banco do Brasil é baseada em Web Services e utiliza o protocolo OAuth 2.0 para autenticação e autorização das requisições.

O código-fonte proposto foi desenvolvido em PHP segundo a especificação para consumir o Web Service fornecido pelo Banco do Brasil.

### Índice
- [Começando...](#começando)
  - [Pré-requisitos](#pr%C3%A9-requisitos)
  - [Exemplo](#exemplo)
- [Estrutura de arquivos e pastas](#estrutura-de-arquivos-e-pastas)
- [Changelog](#changelog)
- [Autor](#autor)
- [Licença](#licen%C3%A7a)


### Começando...
Você precisa ter as seguintes bibliotecas do PHP instaladas para usar esta implementação.

#### Pré-requisitos
  - Curl (php-curl)
  - Json (php-json)
  - XML (php-xml)

#### Exemplo
Baixe primeiramente a implementação:

```sh
$ git clone https://github.com/recovieira/bbboletowebservice minhaimplementacao
$ cd minhaimplementacao
```

Primeiramente, você deve modificar o arquivo "index.php" para atender às suas necessidades.


##### Atenção

**A credencial ("Client Id" com "Secret Key") para registro no ambiente de produção é diferente daquela para registro no ambiente de testes (homologação). A tentativa de obter a token de acesso ("access token") usando uma credencial incompatível com o ambiente no qual a biblioteca foi configurada para tentar registrar o boleto ocasionará a emissão da mensagem "Identificador ou credencial inválidos" pelo Web Service do Banco do Brasil.** Obtenha uma credencial para cada ambiente que você esteja tentando registrá-los.


Posteriormente, execute o exemplo contido seja pela Web ou pela CLI. Para executar pela linha de comando, dê o comando seguinte (neste caso, você precisará ter o pacote "php-cli" instalado):

```sh
$ php index.php
```

### Estrutura de arquivos e pastas
Estrutura breve do conteúdo:

```
├── index.php						# contém um exemplo simples de uso
├── Nova Cobrança - Manual de Integração v1.4.pdf	# especificação do Banco do Brasil
└── lib							# pasta contendo a classe
    └── bb.php						# arquivo contendo a implementação da requisição do serviço
```

### Changelog
#### V 1.0
Lançamento inicial.

#### V 1.1
Evita requisitar uma token toda vez que for registrar um boleto. Armazena a token em cache e requisita uma nova automaticamente quando ela estiver preste a expirar ou já tiver expirado.

#### V 1.2
Permite separar entre o ambiente de teste (pelo método "alterarParaAmbienteDeTestes()") ou de produção (pelo "alterarParaAmbienteDeProducao()"). O exemplo no arquivo "index.php" usa o ambiente de testes.

### Autor
Reginaldo Coimbra Vieira (recovieira@gmail.com)

### Licença
O **Cliente WebService para registro de boletos no Banco do Brasil** é licenciado sob a Licença MIT (MIT). Você pode usar, copiar, modificar, integrar, publicar, distribuir e/ou vender cópias dos produtos finais, mas deve sempre declarar que Reginaldo Coimbra Vieira (recovieira@gmail.com) é o autor original destes códigos e atribuir um link para https://github.com/recovieira/bbboletowebservice.git.
