<?php
class BBBoletoWebService {
	// URL para obtenção do token para registro de boleto
	static private $_tokenURL = 'https://oauth.hm.bb.com.br/oauth/token';

	// URL para registro de boleto
	static private $_url = 'https://cobranca.homologa.bb.com.br:7101/registrarBoleto';

	private $_clientid;
	private $_secret;

	// Tempo limite para obter resposta de 20 segundos
	private $_timeout = 20;

	/**
	 * Construtor do Consumidor de WebService do BB
	 * @param string $clientid Identificação do requisitante
	 * @param string $secret Segredo ("Senha") do requisitante
	 */
	function __construct($clientid, $secret) {
		$this->_clientid	=& $clientid;
		$this->_secret		=& $secret;
	}

	/**
	 * Alterar o tempo máximo para aguardar resposta
	 * @param int $timeout	Tempo > 0 (em segundos) para aguardar resposta
	 */
	function alterarLimiteDeResposta($timeout) {
		$this->_timeout =& $timeout;
	}

	/**
	 * Inicia as configurações do Curl útil para
	 * realizar as requisições de token e registro de boleto
	 * @returns resource Curl pré-configurado
	 */
	private function _prepararCurl() {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_BINARYTRANSFER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_POST => true,
			CURLOPT_TIMEOUT => $this->_timeout,
			CURLOPT_MAXREDIRS => 3
		));
		return $curl;
	}

	/**
	 * Inicia as configurações do Curl útil para
	 * realizar as requisições de token e registro de boleto
	 * @returns object|bool Objeto, caso o token foi recebido com êxito, ou false, caso contrário
	 */
	function obterToken() {
		$curl = self::_prepararCurl();
		curl_setopt_array($curl, array(
			CURLOPT_URL => self::$_tokenURL,
			CURLOPT_POSTFIELDS => 'grant_type=client_credentials&scope=cobranca.registro-boletos',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Basic ' . base64_encode($this->_clientid . ':' . $this->_secret),
				'Cache-Control: no-cache'
			)
		));
		$resposta = curl_exec($curl);
		curl_close($curl);

		// Recebe os dados do WebService no formato JSON.
		// Realiza o parse da resposta e retorna.
		// Caso seja um valor vazio ou fora do formato, retorna false.
		return json_decode($resposta);
	}

	/**
	 * Passa por todos os nós do XML e retorna no formato de array
	 * considerando apenas o valor do nó (nodeValue) e o nome do
	 * nó (nodeName sem namespace)
	 * @param DOMNode $no		Nó a ser percorrido pela função
	 * @param Array &$resultado	Variável que deverá armazenar o resultado encontrado
	 * @returns array Transcrição do formato XML em array
	 */
	static private function _parseXML($no, &$resultado) {
		if ($no->firstChild && $no->firstChild->nodeType == XML_ELEMENT_NODE)
			foreach ($no->childNodes as $pos)
				self::_parseXML($pos, $resultado[$pos->localName]);
		else
			$resultado = html_entity_decode(trim($no->nodeValue));
	}

	/**
	 * Recebe um array contendo o mapeamento "campo WSDL" -> "valor", conforme
	 * descrito na página 18 e 19 da especificação do WebService, realiza a chamada
	 * e retorna o resultado do Banco do Brasil no formato array ao invés de XML.
	 * @param array $data	Array com mapeamento nome -> valor conforme descrito na página 18 e 19 da especificação (vide)
	 * @param string $token Token recebida após requisição ao método "obterToken"
	 * @returns array|bool Transcrição da resposta do WebService em array ou "false" em caso de falha
	 */
	function registrarBoleto($parametros, $token) {
		// Montar envelope contendo a requisição do serviço
		$requisicao = '<?xml version="1.0" encoding="UTF-8"?><SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.tibco.com/schemas/bws_registro_cbr/Recursos/XSD/Schema.xsd"><SOAP-ENV:Body><xsd:requisicao>';

		// Coloca cada parâmetro na requisição
		foreach ($parametros as $no => &$valor)
			$requisicao .= "<xsd:$no>" . htmlspecialchars($valor) . "</xsd:$no>";

		// Fecha o nó da requisição, o corpo da mensagem e o envelope
		$requisicao .= '</xsd:requisicao></SOAP-ENV:Body></SOAP-ENV:Envelope>';

		// Preparar requisição
		$curl = self::_prepararCurl();
		curl_setopt_array($curl, array(
			CURLOPT_URL => self::$_url,
			CURLOPT_POSTFIELDS => &$requisicao,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: text/xml;charset=UTF-8',
				"Authorization: Bearer $token",
				'SOAPAction: registrarBoleto'
			)
		));
		$resposta = curl_exec($curl);
		curl_close($curl);

		// Criar documento XML para percorrer os nós da resposta
		$dom = new DOMDocument('1.0', 'UTF-8');
		// Verificar se o formato recebido é um XML válido.
		// A expressão regular executada por "preg_replace" retira espaços vazios entre tags.
		if (@$dom->loadXML(preg_replace('/(?<=>)\\s+(?=<)/s', '', $resposta))) {
			// Realiza o "parse" da resposta a partir do primeiro nó no
			// corpo do documento dentro do envelope
			self::_parseXML($dom->documentElement->firstChild->firstChild, $resultado);

			return $resultado;
		}

		// Retorna "false" em caso de falha
		return false;
	}
}
