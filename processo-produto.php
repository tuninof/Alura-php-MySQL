<?php 

require "src/conexcao-bd.php";
require "src/Modelo/Produto.php";
require "src/Repositorio/ProdutoRepositorio.php";

$produtoRepositorio = new ProdutoRepositorio($pdo);

if($_POST["type"] === "excluir") {
    $produtoRepositorio->deletarProduto($_POST["id"]);
}

elseif ($_POST["type"] === "editar") {
    $produto = new Produto($_POST["id"], $_POST["tipo"], $_POST["nome"], $_POST["descricao"], $_POST["preco"]);



    if ($_FILES["imagem"]['error'] == UPLOAD_ERR_OK) {
        $produto->setImagem(uniqid() . $_FILES["imagem"]["name"]);
        move_uploaded_file($_FILES["imagem"]["tmp_name"], $produto->getImagemDiretorio());
    }else {
        $produtoBanco = $produtoRepositorio->buscarProduto($_POST["id"]);
        $produto->setImagem($produtoBanco->getImagem());
    }
    
    $produtoRepositorio->editarProduto($produto);
}


header("location: admin.php");