<?php 

class ProdutoRepositorio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function formarObjeto($dados)
    {
        return new Produto($dados['id'],
            $dados['tipo'],
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['imagem']);
    }

    public function opcoesCafe():array
    {
        $statement = $this->pdo->query("SELECT * FROM produtos WHERE tipo = 'Café' ORDER BY preco");
        $produtosCafe = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosCafe = array_map(function($produto)
        {
            return $this->formarObjeto($produto);

        }, $produtosCafe);

        return $dadosCafe;
    }

    public function opcoesAlmoco():array
    {
        $statement = $this->pdo->query("SELECT * FROM produtos WHERE tipo = 'Almoço' ORDER BY preco");
        $produtosAlmoco = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosAlmoco = array_map(function($produto)
        {
            return $this->formarObjeto($produto);
        }, $produtosAlmoco);

        return $dadosAlmoco;
    }

    public function listaProdutos():array
    {
        $statement = $this->pdo->query("SELECT * FROM produtos ORDER BY preco");
        $produtos = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosProdutos = array_map(function($produto)
        { 
            return $this->formarObjeto($produto);
        }, $produtos);

        return $dadosProdutos;
    }

    public function deletarProduto($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public function salvarProduto(Produto $produto)
    {
        $statement = $this->pdo->prepare("INSERT INTO produtos (nome, tipo, descricao, preco, imagem) VALUES (:nome, :tipo, :descricao, :preco, :imagem)");
        $statement->bindValue(":nome", $produto->getNome());
        $statement->bindValue(":tipo", $produto->getTipo());
        $statement->bindValue(":descricao", $produto->getDescricao());
        $statement->bindValue(":preco", $produto->getPreco());
        $statement->bindValue(":imagem", $produto->getImagem());
        $statement->execute();

    }

    public function buscarProduto($id):Produto
    {
        $statement = $this->pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        $statement->bindValue(1, $id);
        $statement->execute();

        $dados = $statement->fetch(PDO::FETCH_ASSOC);

        $produto = $this->formarObjeto($dados);

        return $produto;
    }

    public function editarProduto(Produto $produto)
    {
        $statement = $this->pdo->prepare("UPDATE produtos SET nome = :nome, tipo = :tipo, descricao = :descricao, preco = :preco, imagem = :imagem WHERE id = :id");
        $statement->bindValue(":nome", $produto->getNome());
        $statement->bindValue(":tipo", $produto->getTipo());
        $statement->bindValue(":descricao", $produto->getDescricao());
        $statement->bindValue(":preco", $produto->getPreco());
        $statement->bindValue(":imagem", $produto->getImagem());
        $statement->bindValue(":id", $produto->getId());
        $statement->execute();

    }
}