<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'bb.php');

// Se for uma requisição na Web, retorna no formato texto plano com suporte à codificação de caracteres UTF-8
header('Content-Type: text/plain;charset=UTF-8');

// Exemplo de preenchimento e uso abaixo. Por favor, não deixe de ler a especificação
// e definir a chamada conforme a sua necessidade.
$convenio = '1234567';
$numerodacarteira = '17';
$variacaodacarteira = '19';
$numerodoboleto = '1';
$datadaemissao = '23.06.2018';					// Segundo a especificação, deve ser no formato DD.MM.AAAA
$datadovencimento = '25.06.2018';				// Segundo a especificação, deve ser no formato DD.MM.AAAA
$valor = '150.35';								// No formato inglês (sem separador de milhar)
$tipodedocumentodocliente = 1;					// 1 para CPF e 2 para CNPJ
$numerodedocumentodocliente = '12345678901';	// CPF ou CNPJ, sem pontos ou traços
$nomedocliente = 'Boleto de Teste';
$enderecodocliente = 'Boleto de Teste';
$bairrodocliente = 'Boleto de Teste';
$municipiodocliente = 'Boleto de Teste';
$sigladoestadodocliente = 'MG';
$cepdocliente = '36212000';						// Sem pontos ou traços
$telefonedocliente = '';

// Cria objeto de BBBoletoWebService para consumo de serviço
$bb = new BBBoletoWebService('coloqueOClientIDAqui', 'coloqueOSecretAqui');

// Realiza a obtenção de token
$token = $bb->obterToken();
if ($token && isset($token->access_token)) {
	echo "Token recebida:\n\n" . $token->access_token . "\n\n\n";
	flush();

	// Exemplo de chamada passando os parâmetros com a token
	$resultado = $bb->registrarBoleto(array(
		'numeroConvenio' => $convenio,
		'numeroCarteira' => $numerodacarteira,
		'numeroVariacaoCarteira' => $variacaodacarteira,
		'codigoModalidadeTitulo' => 1,
		'dataEmissaoTitulo' => $datadaemissao,
		'dataVencimentoTitulo' => $datadovencimento,
		'valorOriginalTitulo' => $valor,
		'codigoTipoDesconto' => 0,
		'codigoTipoJuroMora' => 0,
		'codigoTipoMulta' => 0,
		'codigoAceiteTitulo' => 'N',
		'codigoTipoTitulo' => 17,
		'textoDescricaoTipoTitulo' => 'Recibo',
		'indicadorPermissaoRecebimentoParcial' => 'N',
		'textoNumeroTituloBeneficiario' => '1',
		'textoNumeroTituloCliente' => '000' . $convenio . sprintf('%010d', $numerodoboleto),
		'textoMensagemBloquetoOcorrencia' => 'Pagamento disponível até a data de vencimento',
		'codigoTipoInscricaoPagador' => $tipodedocumentodocliente,
		'numeroInscricaoPagador' => $numerodedocumentodocliente,
		'nomePagador' => $nomedocliente,
		'textoEnderecoPagador' => $enderecodocliente,
		'numeroCepPagador' => $cepdocliente,
		'nomeMunicipioPagador' => $municipiodocliente,
		'nomeBairroPagador' => $bairrodocliente,
		'siglaUfPagador' => $sigladoestadodocliente,
		'textoNumeroTelefonePagador' => $telefonedocliente,
		'codigoChaveUsuario' => 1,
		'codigoTipoCanalSolicitacao' => 5
	), $token->access_token);

	echo "Parse do resultado:\n\n";
	print_r($resultado);
} else
	echo 'Falha ao receber a token.';

flush();
