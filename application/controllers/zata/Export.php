<?php

/** 
 * ZATA
 * 
 * Uma estrutura baseada na framework codeiginiter para o desenvolvimento
 * de aplicativos que proporciona a criação de soluções de forma rápida
 * e inovadora, reduzindo o tempo de desenvolcimento em 80%.
 * 
 * Este conteudo é publicado sob a Lincença MIT
 *
 * Copyright(c) 2015-2017, TRIVEE SERVICES IT
 * 
 * É concedida permissão a qualquer pessoa que obtenha uma cópia deste 
 * software e arquivos de documentação associados, sem restrições e limitação,
 * incluindo os direitos de copiar, modificar, fundir, publicar, 
 * distribuir, sublicenciar e/ou vender.
 *
 * O aviso de copyright acima a este aviso de permissão devem ser incluidos
 * em todas as cópias ou partes substancias do software.
 *
 * O SOFTWARE É FORNECIDO "COMO ESTÁ", SEM GARANTIA DE QUALQUER TIPO,
 * EXPRESSA OU IMPLÍCITA, INCLUINDO, MAS NÃO SE LIMITANDO AS GARANTIAS
 * DE COMERCIALIZAÇÃO, ADEQUAÇÃO A UMA FINALIZADE ESPECIFICA E NÃO VIOLAÇÃO.
 *
 * EM NENHUMA CIRCUNSTÂNCIA, AUTORES OU TITULARES DE DIREITOS DE AUTOR SERÃO
 * RESPONSÁVEIS POR QUALQUER RECLAMAÇÃO, DANOS OU OUTRAS RESPONSABILIDADES,
 * SEJA EM UMA AÇÃO DE CONTRATO, ATO OU DE OUTRA FORMA DECORRENTE DE FORA, OU
 * EM CONEXÃO COM OUTRO SOFTWARE OU O USO OU OUTROS NEGECIOS
 *
 * ZATA
 *
 * A framework based on the codeiginiter framework for development
 * application that provides the creation of solutions quickly
 * and innovative, reducing the time of development by 80%.
 *
 * This content is published under the MIT Licensing
 *
 * Copyright (c) 2015-2017, TRIVEE SERVICES IT
 *
 * Permission is granted to anyone who obtains a copy of this
 * software and associated documentation files, without restrictions and limitations,
 * including the rights to copy, modify, merge, publish,
 * distribute, sublicense and / or sell.
 *
 * The above copyright notice to this permission notice must be included
 * on all copies or any parts of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * OF MERCHANTABILITY, FITNESS FOR A SPECIFIC FINISH AND NON-INFRINGEMENT.
 *
 * IN NO EVENT SHALL AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * RESPONSIBLE FOR ANY CLAIM, DAMAGES OR OTHER RESPONSIBILITIES,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE ARISING OUTSIDE, OR
 * IN CONNECTION WITH OTHER SOFTWARE OR THE USE OR OTHER DEALINGS
 *
 * @package   Zata
 * @author    Ralny Andrade | <ra@trivee.com.br> | https://github.com/ralny
 * @copyright TRIVEE SERVICES IT MEI | Copyright (c) 2015 - 2016
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      http://www.trivee.com.br
 * @since     Versão 1.0.0 
 */

header("Content-Type: text/html; charset=utf-8",true);

defined('BASEPATH') or exit('Não é permitido acesso direto ao script');

/**
 * Class Export 
 *
 * Camada responsável por controlar a exportação de dados 
 *
 * @category  Controllers
 * @author    Ralny Andrade | <ra@trivee.com.br> | https://github.com/ralny
 */
class Export extends MY_Controller
{
	/**
	 * Método construtor
	 *
	 * @access  public
	 * @return  void
	 */
	function __construct()
	{
		parent::__construct();

		/**
		 * Identificador da empresa de origem do registro
		 * Company identifier of record source
		 */
		$this->company = $this->session->userdata('token_company');

		/**
		 * Carregando model
		 *
		 */
		$this->load->model('zata/export_model');
	}

	/**
	 * get_csv_eventos_utilizacao_de_salas
	 * 
	 * Faz a exportacao de utilização de salas em .CSV
	 */
	function get_csv_eventos_utilizacao_de_salas()
	{
		/**
		 * Nome do Arquivo
		 */
		$file_name = 'ZATA_EVENTOS_utilizacao_de_sala_'.date("Y-m-d h:i:s").'.csv';
		
		/**
		 * Definiçaõ do header
		 */
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$file_name.'"');

		/**
		 * Dados que vão ser exportados
		 */
		$query = $this->export_model->eventos_utilizacao_de_salas_csv();

		/**
		 * Configurações
		 */
		$delimiter = ",";
		$newline = "\r\n";
		$data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
		$data = mb_convert_encoding($data , "UTF-8", "UTF-8, ISO-8859-1, ISO-8859-15");
   
	    force_download($file_name, $data);
	   
	}//End Function

	/**
	 * get_xml_eventos_utilizacao_de_salas
	 * 
	 * Faz a exportacao de utilização de salas em .XML
	 */
	function get_xml_eventos_utilizacao_de_salas()
	{
		/**
		 * Nome do Arquivo
		 */
		$file_name = 'ZATA_EVENTOS_utilizacao_de_sala_'.time().'.xml';
		
		/**
		 * Dados que vão ser exportados
		 */
		$query = $this->export_model->eventos_utilizacao_de_salas_csv();

		
			$config = array ($config = array (         
								'root'     => 'root',  
								'element'  => 'element',
								'newline'  => "\n", 
								'tab'           => "\t" )
							); 
					

			$data = $this->dbutil->xml_from_result($query,$config);

			$data = mb_convert_encoding($data , "UTF-8", "UTF-8, ISO-8859-1, ISO-8859-15");			

			write_file($file_name, $data); 

			force_download($file_name,$data);
	   
	}//End Function

	/**
	 * exportExcelData
	 * Função auxiliar para exportação em xls
	 */
	public function exportExcelData($records)
	{
	 	$heading = false;
			if (!empty($records))
			{
			
				foreach ($records as $row)
				{
					if (!$heading)
					{
					   // Exibe os nomes dos campos/colunas na primeira linha
					   echo implode("\t", array_keys($row)) . "\n";
					   $heading = true;
				    }
				    echo implode("\t", ($row)) . "\n";
				}
			}	
	}//End Function

	/**
	 * get_xls_eventos_utilizacao_de_salas
	 * 
	 * Faz a exportacao de utilização de salas em .XLS
	 */
	public function get_xls_eventos_utilizacao_de_salas(){

		/** 
		 * Model dos dados que irão ser exportados
		 */
		$data = $this->export_model->eventos_utilizacao_de_salas_xls();

	 	$dataToExports = [];

		foreach ($data as $row)
		{
	        $arrangeData['Utilizacao de Sala'] = mb_convert_encoding($row['desc_utilizacao_sala'],'utf-16','utf-8');
			$arrangeData['Definicao'] 		   = mb_convert_encoding($row['desc_definicao'],'utf-16','utf-8');
	  		$dataToExports[]	 			   = $arrangeData;
		}
		
		/***
		 * Definir o nome do arquivo
		 */
		$filename = "tpl_exp_Eventos_utilizacao_salas.xls";
		
		/**
		 * Definiçaõ do header
		 */
		header("Content-Type: application/vnd.ms-excel;charset = UTF-8");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Pragma: no-cache");
		header("Expires: 0");
		 
		/**
		 * exportExcelData
		 * Função auxiliar para exportação em xls
		 * 
		 */
		$this->exportExcelData($dataToExports);
	}

	/**
	 * Faz a exportação PDF da listagem de utilização de salas
	 */
	function get_pdf_eventos_utilizacao_de_salas()
	{
		/***
		 * Carregando a view
		 */
		$html = $this->load->view('print/eventos/utilizacao_salas_lista_pdf', [], true);

		/***
		 * Definir o nome do arquivo
		 */
		$filename = "utilizacao_salas_lista-" . time();

		/***
		 * Metodo responsavel por renderizar um pagina html ou php em PDF
		 */
		$this->pdfgenerator->generate($html, $filename, true, 'A4', 'portrait');

	 } //End Function




}//End Class
