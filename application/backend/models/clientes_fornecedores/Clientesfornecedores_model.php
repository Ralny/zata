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

defined('BASEPATH') OR exit('Não é permitido acesso direto ao script');

/**
 * Class Model Clientesfornecedores 
 *
 * Camada base de abstração e persistências dos dados da aplicação no banco. 
 * As interações CRUD ocorrem na CORE/MY_model.
 * Nessa classe sao adcionadas somente as funcoes especificas referentes ao controller
 * Base layer of abstraction and persistence of application data in the database.
 * CRUD interactions occur in CORE / MY_model.
 * In this class only the specific functions related to the controller
 *
 * @category  Models
 * @author    Ralny Andrade | <ra@trivee.com.br> | https://github.com/ralny
 */
class Clientesfornecedores_model extends MY_Model {

   /**
    * Class constructor
    *
    * @return  void
    */
    public function __construct()
    {
        
        parent::__construct();

        /**
         * Definir o nome da tabela
         * Set the table name
         */
        $this->table    = "cli_cliente_fornecedor";
        
        /**
         * Definir o nome da chave primaria
         * Define the primary key name
         */
        $this->primaryKey = "id_cf";
        
    }

    /**
     * Listar Clientes
     * List Providers
     */
    public function listar_clientes() {
 
        $sql = "
                SELECT * FROM cli_cliente_fornecedor WHERE (tipo_cadastro = 'Cliente' OR tipo_cadastro = 'Ambos') AND token_company = '$this->company'
                ";   
        //execultando a consulta
        $query = $this->db->query($sql);
        //retornando dados da consulta para o controller
        return $query->result();
    }

    /**
     * Listar Fornecedores
     * List Providers
     */
    public function listar_fornecedores() {
 
        $sql = " 
                SELECT * FROM cli_cliente_fornecedor WHERE (tipo_cadastro = 'Fornecedor' OR tipo_cadastro = 'Ambos') AND token_company = '$this->company'
                ";   
        //execultando a consulta
        $query = $this->db->query($sql);
        //retornando dados da consulta para o controller
        return $query->result();
    }
    
      /**
     * Buscar NCM Autocomplete 
     *
     * Retorna o numero do ncm buscado
     * @param $q String com fragmento buscado.
     * @return array com os dados encontrados
     */
     public function buscar_cliente_autocomplete($q){
        /**
         * Montando select
         * Obs: ira buscar em duas colunas, razao_social e nome_fantasia
         */
        $this->db->select('*');
        $this->db->like('razao_social', $q);
        $this->db->or_like('nome_fantasia', $q);
        $this->db->limit(5);
        $query = $this->db->get('cli_cliente_fornecedor');

        /**
         * Se existir resultados
         */
        if ($query->num_rows() > 0)
        {
            /**
             * Listando resultados 
             */
            foreach ($query->result_array() as $row)
            {
                $row_set[] = array('label'=>$row['nome_fantasia'],'id'=>$row['id_cf'], 'telefone'=>$row['telefone']); 
            }

           
            /**
             * JSON - Retorna a lista de registros encontrados para o controller
             */
            echo json_encode($row_set);
        }
    }
 


        




    
}//Fim da classe
