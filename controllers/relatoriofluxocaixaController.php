<?php
class relatoriofluxocaixaController extends controller{

    // Protected - estas variaveis só podem ser usadas nesse arquivo
    protected $table = "fluxocaixa";
    protected $nome = "relatoriofluxocaixa";
    protected $colunas;
    
    protected $model;
    protected $shared;
    protected $usuario;

    public function __construct() {
        
        // Instanciando as classes usadas no controller
        $this->shared = new Shared($this->table);
        $tabela = ucfirst($this->table);
        $this->model = new Relatoriofluxocaixa();
        $this->usuario = new Usuarios();
        
        $this->colunas = $this->shared->nomeDasColunas();


        // verifica se tem permissão para ver esse módulo
        if(in_array("relatoriofluxocaixa_ver", $_SESSION["permissoesUsuario"]) == false){
            header("Location: " . BASE_URL . "/home"); 
        }
        // Verificar se está logado ou nao
        if($this->usuario->isLogged() == false){
            header("Location: " . BASE_URL . "/login"); 
        }
    }
     
    public function index() {
        
        if(isset($_POST) && !empty($_POST)){ 
            
            $id = addslashes($_POST['id']);
            if(in_array("relatoriofluxocaixa_exc", $_SESSION["permissoesUsuario"]) == false || empty($id) || !isset($id)){
                header("Location: " . BASE_URL . "/relatoriofluxocaixa"); 
            }
            if($this->shared->idAtivo($id) == false){
                header("Location: " . BASE_URL . "/relatoriofluxocaixa"); 
            }
            $this->model->excluir($id);
            header("Location: " . BASE_URL . "/relatoriofluxocaixa");
        }
        
        $dados['infoUser'] = $_SESSION;
        $dados["colunas"] = $this->colunas;
        $dados["meta"] = $this->model->meta();
        $dados["labelTabela"]["labelBrowser"] = 'Relatório de Fluxo de Caixa';

        $this->loadTemplate('relatoriofluxocaixa', $dados);
    }
    
    public function adicionar() {
        
        if(in_array("relatoriofluxocaixa_add", $_SESSION["permissoesUsuario"]) == false){
            header("Location: " . BASE_URL . "/relatoriofluxocaixa"); 
        }
    
        $dados['infoUser'] = $_SESSION;

        if(isset($_POST) && !empty($_POST)){  
            $this->model->adicionar($_POST);
            header("Location: " . BASE_URL . "/" . $this->table ."/adicionar");
        }else{ 
            $dados["colunas"] = $this->colunas;
            $dados["viewInfo"] = ["title" => "Adicionar"];
            $dados["labelTabela"] = $this->shared->labelTabela();
            $this->loadTemplate($this->table . "-form", $dados);
        }
    }
    
    public function editar($id) {

        if(in_array($this->table . "_edt", $_SESSION["permissoesUsuario"]) == false || empty($id) || !isset($id)){
            header("Location: " . BASE_URL . "/" . $this->table); 
        }

        if($this->shared->idAtivo($id) == false){
            header("Location: " . BASE_URL . "/" . $this->table); 
        }

        $dados['infoUser'] = $_SESSION;
        
        if(isset($_POST) && !empty($_POST)){
            $this->model->editar($id, $_POST);
            header("Location: " . BASE_URL . "/" . $this->table); 
        }else{
            $dados["item"] = $this->model->infoItem($id); 
            $dados["colunas"] = $this->colunas;
            $dados["viewInfo"] = ["title" => "Editar"];
            $dados["labelTabela"] = $this->shared->labelTabela();
            $this->loadTemplate($this->table . "-form", $dados); 
        }
    }

    public function quitar () {

        if(isset($_POST) && !empty($_POST)){
            echo json_encode($this->model->quitar($_POST));
        }
    }

    public function excluirChecados () {

        if(isset($_POST) && !empty($_POST)){
            echo json_encode($this->model->excluirChecados($_POST));
        }
    }

    public function inlineEdit () {

        if(isset($_POST) && !empty($_POST)){
            echo json_encode($this->model->inlineEdit($_POST));
        }
    }
    

}   
?>