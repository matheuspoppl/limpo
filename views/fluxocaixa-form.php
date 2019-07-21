<?php $modulo = str_replace("-form", "", basename(__FILE__, ".php")) ?>
<script type="text/javascript">
    var baselink = '<?php echo BASE_URL;?>',
        currentModule = '<?php echo $modulo ?>',
        campoPesquisa = '', // aqui vai o campo de id-usuario caso seja necessário filtrar o datatable somente para os registros referentes ao usuário logado
        valorPesquisa = '<?php echo in_array('podetudo_ver', $_SESSION['permissoesUsuario']) ? "" : $_SESSION["idUsuario"]; ?>';
</script>

<!-- Chama o arquivo específico do módulo, caso não exista,  -->
<!-- Este javaScript serve para fazer verificações inerentes à cada módulo, por exemplo o radio de Clientes -->
<script src="<?php echo BASE_URL?>/assets/js/<?php echo $modulo?>.js" type="text/javascript"></script>
<?php if (isset($_SESSION["returnMessage"])): ?>

    <div class="alert <?php echo $_SESSION["returnMessage"]["class"] ?> alert-dismissible">
    
        <?php echo $_SESSION["returnMessage"]["mensagem"] ?>
    
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>

<?php endif?>
<header class="d-lg-flex align-items-center my-5">
    <h1 class="display-4 m-0 text-capitalize font-weight-bold"><?php echo $viewInfo["title"]." ".ucfirst($labelTabela["labelForm"]); ?></h1>
</header>

<?php $table = false ?>
<?php

    //
    // Configurações vindas da modal que está importando este arquivo
    //
    
    $formId = "";
    if (isset($formIdModal)) {
        $formId = $formIdModal;
    }

?>

<section class="mb-5">
    <form id="form-principal<?php echo $formId ?>" method="DELETE" class="needs-validation" autocomplete="off" novalidate>
        <div class="row">
            <?php foreach ($colunas as $key => $value): ?>
                <?php if(isset($value["Comment"]) && array_key_exists("form", $value["Comment"]) && $value["Comment"]["form"] != "false") : ?>
                    
                    <!-- INÍCIO DOS TESTES PARA VER QUAL O TIPO DE CAMPO -->
                    <!-- CAMPOS DO TIPO TABELA - Ex: CONTATOS -->
                    <?php if(array_key_exists("type", $value["Comment"]) && $value["Comment"]["type"] == "table"): ?> 
                        
                        <!-- Label Geral -->
                        <label class="d-none"><span><?php echo array_key_exists("label", $value["Comment"]) ? $value["Comment"]["label"] : ucwords(str_replace("_", " ", $value['Field'])) ?></span></label>
                        
                        <?php $table = true ?>
                        
                        <input 
                            type="hidden" 
                            name="<?php echo $value["Field"] ?>" 
                            value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : "" ?>"
                            data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                            data-mascara_validacao = "<?php echo array_key_exists("mascara_validacao", $value["Comment"]) ? $value["Comment"]["mascara_validacao"] : "false" ?>"
                            <?php echo $value["Null"] == "NO" ? "required" : "" ?>
                            
                        />

                    <!-- CAMPOS DO TIPO HIDDEN - Ex: ALTERAÇÕES -->
                    <?php elseif(array_key_exists("type", $value["Comment"]) && $value["Comment"]["type"] == "hidden"): ?>
                        
                        <input 
                            type="hidden" 
                            name="<?php echo lcfirst($value["Field"]) ?>" 
                            value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : "" ?>"
                            data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                            data-mascara_validacao = "<?php echo array_key_exists("mascara_validacao", $value["Comment"]) ? $value["Comment"]["mascara_validacao"] : "false" ?>"
                            <?php echo $value["Null"] == "NO" ? "required" : "" ?>
                        />
                    <?php else: ?>
                        <div    class="col-lg-<?php echo isset($value["Comment"]["column"]) ? $value["Comment"]["column"] : "12" ?>" 
                                style="order:<?php echo isset($value["Comment"]["ordem_form"]) ? $value["Comment"]["ordem_form"] : 100 ?>;">
                            <div class="form-group">

                                <!-- Label Geral -->
                                <label class="fluxocaixa <?php echo $value["Null"] == "NO" ? "font-weight-bold" : "" ?>" for="<?php echo $value['Field'] ?>">
                                    
                                    <!-- Asterisco de campo obrigatorio -->
                                    <?php if ($value["Null"] == "NO"): ?>
                                        <i class="font-weight-bold" data-toggle="tooltip" data-placement="top" title="Campo Obrigatório">*</i>
                                    <?php endif ?>
                                    <span><?php echo array_key_exists("label", $value["Comment"]) ? $value["Comment"]["label"] : ucwords(str_replace("_", " ", $value['Field'])) ?></span>
                                </label>
                                
                                <!-- CAMPOS DO TIPO RELACIONAL - SELECT -->
                                <?php if(array_key_exists("type", $value["Comment"]) && $value["Comment"]["type"] == "relacional"): ?>
                                    
                                    <select id="<?php echo lcfirst($value['Field']);?>" 
                                            name="<?php echo lcfirst($value['Field']);?>"
                                            class="form-control"
                                            data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                            tabindex="<?php echo isset($value["Comment"]["ordem_form"]) ? $value["Comment"]["ordem_form"] : "" ?>"
                                            data-mascara_validacao = "<?php echo array_key_exists("mascara_validacao", $value["Comment"]) ? $value["Comment"]["mascara_validacao"] : "false" ?>"
                                            <?php echo $value['Null'] == "NO" ? "required" : "" ?>
                                            >
                                            <option value="" selected >Selecione</option>
                                            <?php for($j = 0; $j < count($value["Comment"]['info_relacional']['resultado']); $j++):?>
                                                
                                                <option value="<?php echo $value["Comment"]['info_relacional']['resultado'][$j][$value["Comment"]['info_relacional']['campo']];?>"
                                                    <?php if(isset($item[$value["Field"]])):?>
                                                        <?php if(strtoupper($item[$value["Field"]]) == strtoupper($value["Comment"]['info_relacional']['resultado'][$j][$value["Comment"]['info_relacional']['campo']])):?>
                                                            <?php echo "selected"?>
                                                        <?php endif?>    
                                                    <?php endif?>    

                                                ><?php echo $value["Comment"]['info_relacional']['resultado'][$j][$value["Comment"]['info_relacional']['campo']];?></option>
                                                
                                            <?php endfor;?>     
                                    </select>

                                <!-- CAMPOS DO TIPO CHECKBOX -->    
                                <?php elseif(array_key_exists("type", $value["Comment"]) && $value["Comment"]["type"] == "checkbox"): ?>

                                        <?php 
                                            $opcoes = $value['Comment']['info_relacional']['resultado'];
                                            $opcoes =  array_map('strtolower', $opcoes);
                                            $opcoes =  array_map('trim', $opcoes);
                                            
                                            $checados = array();
                                            if(isset($item) && !empty($item)){
                                                $checados =  explode(",",$item[$value['Field']]);
                                                $checados =  array_map('strtolower', $checados);
                                                $checados =  array_map('trim', $checados);
                                            }
                                        ?>
                                        <div class="form-check-wrapper position-relative form-checkbox pr-4" tabindex="0">
                                            <?php for($j = 0; $j < count($opcoes); $j++):?>
                                                <div class="form-check form-check-inline">
                                                    <input 
                                                        id="<?php echo $value["Comment"]['info_relacional']['resultado'][$j];?>" 
                                                        type="checkbox" 
                                                        class="form-check-input" 
                                                        tabindex="<?php echo isset($value["Comment"]["ordem_form"]) ? $value["Comment"]["ordem_form"] : "" ?>"
                                                        value="<?php echo $value["Comment"]['info_relacional']['resultado'][$j];?>"
                                                        data-mascara_validacao = "<?php echo array_key_exists("mascara_validacao", $value["Comment"]) ? $value["Comment"]["mascara_validacao"] : "false" ?>" 
                                                        <?php
                                                        if(isset($item)){
                                                            if( in_array($opcoes[$j], $checados) == true){
                                                                echo "checked='checked'";
                                                            }
                                                        }
                                                        ?>
                                                    />
                                                    <label class="form-check-label" for="<?php echo $value["Comment"]['info_relacional']['resultado'][$j];?>" ><?php echo $value["Comment"]['info_relacional']['resultado'][$j];?></label>
                                                </div>
                                            <?php endfor?>
                                            <input 
                                                type="hidden" 
                                                name="<?php echo lcfirst($value["Field"]) ?>" 
                                                value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : "" ?>"
                                                data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                                data-mascara_validacao = "<?php echo array_key_exists("mascara_validacao", $value["Comment"]) ? $value["Comment"]["mascara_validacao"] : "false" ?>"
                                                <?php echo $value["Null"] == "NO" ? "required" : "" ?>
                                            />
                                        </div>
                                
                                <!-- CAMPOS DO TIPO TEXTAREA -->
                                <?php elseif(array_key_exists("type", $value["Comment"]) && $value["Comment"]["type"] == "textarea"): ?>
                                    
                                    <textarea
                                        class="form-control" 
                                        name="<?php echo lcfirst($value['Field']);?>" 
                                        data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                        tabindex="<?php echo isset($value["Comment"]["ordem_form"]) ? $value["Comment"]["ordem_form"] : "" ?>"
                                        id="<?php echo lcfirst($value['Field']);?>"
                                        data-mascara_validacao = "<?php echo array_key_exists("mascara_validacao", $value["Comment"]) ? $value["Comment"]["mascara_validacao"] : "false" ?>"
                                        <?php echo $value['Null'] == "NO" ? "required" : "" ?>
                                    ><?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : "" ?></textarea>

                                <!-- CAMPOS DO TIPO RADIO -->
                                <?php elseif(array_key_exists("type", $value["Comment"]) && $value["Comment"]["type"] == "radio"): ?>
                                    <?php $indexRadio = 0 ?>
                                    <div class="form-check-wrapper form-radio d-table position-relative pr-4" tabindex="0">
                                        <?php foreach ($value["Comment"]["options"] as $valueRadio => $label): ?>
                                            <div class="form-check form-check-inline position-static">
                                                <input 
                                                    type="radio" 
                                                    id="<?php echo $valueRadio ?>" 
                                                    value="<?php echo $valueRadio ?>" 
                                                    name="<?php echo $value["Field"] ?>" 
                                                    tabindex="<?php echo isset($value["Comment"]["ordem_form"]) ? $value["Comment"]["ordem_form"] : "" ?>"
                                                    data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                                    data-mascara_validacao = "<?php echo array_key_exists("mascara_validacao", $value["Comment"]) ? $value["Comment"]["mascara_validacao"] : "false" ?>"
                                                    class="form-check-input" 
                                                    <?php echo $value['Null'] == "NO" ? "required" : "" ?>

                                                    <?php if(isset($item[$value["Field"]])):?>
                                                        <?php if(strtolower($item[$value["Field"]]) == strtolower($valueRadio) ):?>
                                                            <?php echo "checked" ?>
                                                        <?php endif?>
                                                    <?php else:?>        
                                                        <?php echo $indexRadio == 0 ? "checked" : "" ?>
                                                        <?php $indexRadio++ ?>
                                                    <?php endif?>
                                                >
                                                <label class="form-check-label" for="<?php echo $valueRadio ?>"><?php echo $label ?></label>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                    
                                <!-- CAMPOS DO TIPO DROPDOWN -->
                                <?php elseif(array_key_exists("type", $value["Comment"]) && $value["Comment"]["type"] == "dropdown"): ?>
                                    <div class="relacional-dropdown-wrapper dropdown">
                                        <label class="d-none"><span><?php echo array_key_exists("label", $value["Comment"]) ? $value["Comment"]["label"] : ucwords(str_replace("_", " ", $value['Field'])) ?></span></label>
                                        <input 
                                        id="<?php echo $value['Field'] ?>" 
                                        name="<?php echo $value['Field'] ?>" 
                                        type="text" 
                                        class="dropdown-toggle form-control relacional-dropdown-input" 
                                        data-toggle="dropdown" 
                                        aria-haspopup="true" 
                                        aria-expanded="false"
                                        tabindex="<?php echo isset($value["Comment"]["ordem_form"]) ? $value["Comment"]["ordem_form"] : "" ?>"
                                        maxlength="<?php echo $value["tamanhoMax"] ?>"
                                        value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : "" ?>"
                                        data-tabela="<?php echo $value["Comment"]["info_relacional"]["tabela"] ?>" 
                                        data-campo="<?php echo $value["Comment"]["info_relacional"]["campo"] ?>" 
                                        data-pode_nao_cadastrado="<?php echo array_key_exists("pode_nao_cadastrado", $value["Comment"]["info_relacional"]) ? $value["Comment"]["info_relacional"]["pode_nao_cadastrado"] : "false" ?>" 
                                        data-mascara_validacao = "<?php echo array_key_exists("mascara_validacao", $value["Comment"]) ? $value["Comment"]["mascara_validacao"] : "false" ?>"
                                        data-unico="<?php echo array_key_exists("unico", $value["Comment"]) && $value["Comment"]["unico"]  == true ? "unico" : "" ?>"
                                        data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                        <?php echo $value['Null'] == "NO" ? "required" : "" ?>
                                        <?php if( array_key_exists("mascara_validacao", $value["Comment"]) && 
                                                 ( $value["Comment"]["mascara_validacao"] == "monetario" || $value["Comment"]["mascara_validacao"] == "porcentagem" )):?>
                                            data-podeZero="<?php echo array_key_exists("pode_zero", $value["Comment"]) && $value["Comment"]["pode_zero"]  == 'true' ? 'true' : 'false' ?>"
                                        <?php endif?> 
                                        />
                                        <label data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent" for="<?php echo $value['Field'] ?>" class="btn btn-sm text-secondary icon-dropdown m-0 toggle-btn dropdown-toggle">
                                            <i class="fas fa-caret-down"></i>
                                        </label>
                                        <div class="dropdown-menu w-100 p-0 list-group-flush relacional-dropdown" aria-labelledby="<?php echo $value["Field"] ?>">
                                            <div class="p-3 nenhum-result d-none">Nenhum resultado encontrado</div>
                                            <div class="dropdown-menu-wrapper"></div>
                                        </div>
                                    </div>
                                <?php else: ?>

                                    <!-- CAMPOS DO TIPO TEXT -->
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="<?php echo lcfirst($value["Field"]) ?>" 
                                        value="<?php echo isset($item) && !empty($item) ? $item[$value["Field"]] : "" ?>"
                                        data-unico="<?php echo array_key_exists("unico", $value["Comment"]) && $value["Comment"]["unico"]  == true ? "unico" : "" ?>"
                                        data-anterior="<?php echo isset($item) ? $item[$value["Field"]] : "" ?>"
                                        id="<?php echo $value['Field'] ?>"
                                        <?php echo $value['Null'] == "NO" ? "required" : "" ?>
                                        maxlength="<?php echo $value["tamanhoMax"] ?>"
                                        tabindex="<?php echo isset($value["Comment"]["ordem_form"]) ? $value["Comment"]["ordem_form"] : "" ?>"
                                        data-mascara_validacao = "<?php echo array_key_exists("mascara_validacao", $value["Comment"]) ? $value["Comment"]["mascara_validacao"] : "false" ?>"
                                        <?php if( array_key_exists("mascara_validacao", $value["Comment"]) && 
                                                 ( $value["Comment"]["mascara_validacao"] == "monetario" || $value["Comment"]["mascara_validacao"] == "porcentagem" )):?>
                                            data-podeZero="<?php echo array_key_exists("pode_zero", $value["Comment"]) && $value["Comment"]["pode_zero"]  == 'true' ? 'true' : 'false' ?>"
                                        <?php endif?>                                        
                                    />
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endif ?>
            <?php endforeach ?>
            <div class="col-lg-6" style="order:14;">
                <div class="form-group">
                    <label for="dia_venc" class="fluxocaixa font-weight-bold" ><span>* Dia Vencimento</span></label>
                    <select id="dia_venc" 
                            name="dia_venc"
                            class="form-control"
                            data-anterior=""
                            tabindex="14"
                            data-mascara_validacao = "false"   
                    >
                        <option value="" selected >Selecione</option>
                        <?php for($j = 1; $j <= 31; $j++):?>
                            <option value="<?php echo $j;?>"><?php echo $j;?></option>        
                        <?php endfor;?>     
                    </select>
                </div>
            </div>

            <div class="col-lg-6" style="order:16;">
                <div class="form-group">
                    <label for="taxa-cartao" class="fluxocaixa font-weight-bold"><span>* Taxa do Cartão</span></label>
                    <input type="text" class="form-control" name="taxa-cartao" value="" id="taxa-cartao" maxlength="20" tabindex="16" data-mascara_validacao="porcentagem" data-podeZero="true" disabled="disabled" data-anterior=""/>
                </div>
            </div>

            <div class="col-lg-6" style="order:16;">
                <div class="form-group">
                    <label for="custo_financ" class="fluxocaixa font-weight-bold" ><span>* Custo Financeiro</span></label>
                    <input type="text" class="form-control" name="custo_financ" value="" id="custo_financ" maxlength="20" tabindex="16" data-mascara_validacao="monetario" data-podeZero="true" disabled="disabled" data-anterior=""/>
                </div>
            </div>
            <div class="col-lg-3" style="order:18;">
                 <div class="form-group">
                    <div class="btn btn-primary btn-block" tabindex="18" id="btn_incluir"> Incluir </div>                                                 
                 </div>                                       
            </div>
            <div class="offset-xl-3 col-xl-3 col-lg-2 offset-lg-4 text-lg-right order-0 order-lg-1">
                <div class="form-group">
                    <div class="btn btn-secondary btn-block mb-5 mb-lg-0" tabindex="-1" role="button" aria-pressed="false" id="btn_limparCampos">
                        <i class="fas fa-eraser mr-2"></i>
                        <span>Limpar Campos</span>
                    </div>
                </div>
            </div>
        </div>
    </form>    
    <div class="mb-5" id="resumo_lancamento">
        <div class="card card-body">
                <div class="col-lg">
                    <div class="row">
                        <div class="col-lg">
                            <div class="card card-body py-2 text-danger text-center">
                                <p class="m-0">Despesa Total</p>
                                <h2 id="despesa_lanc"></h2>
                            </div>
                        </div>
                        <div class="col-lg">
                            <div class="card card-body py-2 text-success text-center">
                                <p class="m-0">Receita Total</p>
                                <h2 id="receita_lanc"></h2>
                            </div>
                        </div>
                        <div class="col-lg">
                            <div class="card card-body py-2 text-center">
                                <p class="m-0">Total</p>
                                <h2 id="total_lanc"></h2>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <div class="my-5 table-responsive" id="tabela_lancamento" style='max-height: 400px; overflow-y:auto'>
        <form  method="POST" autocomplete="off" novalidate id='form_lancamento'>
            <table class="table table-striped table-hover bg-white table-nowrap first-column-fixed">
                <thead>
                    <tr>
                        <?php foreach ($colunas as $key => $value): ?> 
                            <?php if(isset($value["Comment"]) && array_key_exists("ver", $value["Comment"]) && $value["Comment"]["ver"] != "false") : ?>
                                <th class="border-top-0">
                                    <span><?php echo (isset($value["Comment"]["label"]) && !is_null($value["Comment"]["label"]) && !empty($value["Comment"]["label"])) ? $value["Comment"]["label"] : ucwords(str_replace("_", " ", $value['Field'])) ?></span>
                                </th>
                            <?php endif ?>
                        <?php endforeach ?>
                    </tr>
                </thead>
                
                    <tbody>

                    </tbody>                                            
            
            </table>
            <button type="submit" id='btn_salvar<?php echo $formId ?>' class='d-none'></button>
        </form>
    </div>
    <div class="row flex-row-reverse bd-highlight" id='lbl_btn_salvar'>
        <div class='col-lg-3'><label  for='btn_salvar<?php echo $formId ?>' class='btn btn-primary btn-lg btn-block'>Salvar</label></div>                                               
    </div>    
    <div class="row">
        <?php if (isset($item)): ?>
        <div class="col-xl-2 col-lg-3">
            <button class="btn btn-dark btn-block" type="button" data-toggle="collapse" data-target="#historico" aria-expanded="false" aria-controls="historico">Histórico de Alterações</button>
        </div>
        <?php endif ?>
    </div>
    <?php include "_historico.php" ?>
</section>