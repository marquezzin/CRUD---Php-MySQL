<?php
// Conexão com o banco de dados usando PDO
$pdo = new PDO("mysql:host=localhost;dbname=nome_do_banco", "root", "");

// Lógica para deletar um cliente
if(isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    $pdo->exec("DELETE FROM clientes WHERE id=$id");
    echo "Deletado com sucesso";
}

// Lógica para inserção de um novo cliente (formulário de inserção)
if (isset($_POST["insert"])) {
    $sql = $pdo->prepare("INSERT INTO clientes (id, nome, email, data_nascimento) VALUES (null, ?, ?, ?)");
    $sql->execute(array($_POST["nome"], $_POST["email"], $_POST["data_nascimento"]));
    echo "Inserido com sucesso";
}

// Lógica para atualização de um cliente (formulário de edição)
if (isset($_POST["update"])) {
    $sql = $pdo->prepare("UPDATE clientes SET nome = ?, email = ?, data_nascimento = ? WHERE id = ?");
    $sql->execute(array($_POST["nome"], $_POST["email"], $_POST["data_nascimento"], $_POST["id"]));
    echo "Atualizado com sucesso";
}

// Se estiver editando um cliente, recupera seus dados para exibir no formulário de edição
$clienteParaEditar = null;
if (isset($_GET["edit"])) {
    $id = (int)$_GET["edit"];
    $sql = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
    $sql->execute(array($id));
    $clienteParaEditar = $sql->fetch(); // Retorna os dados do cliente a ser editado
}
?>

<!-- Formulário de Inserção -->
<h2>Adicionar Novo Cliente</h2>
<form method="post">
    <label>Nome:</label>
    <input type="text" name="nome" required><br>

    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>Data de Nascimento:</label>
    <input type="date" name="data_nascimento" required><br>

    <input type="hidden" name="insert" value="1">
    <input type="submit" value="Inserir Cliente">
</form>

<hr>

<!-- Exibir formulário de edição apenas se o usuário clicar em "Editar" -->
<?php if ($clienteParaEditar): ?>
    <h2>Editar Cliente</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $clienteParaEditar['id']; ?>">

        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo $clienteParaEditar['nome']; ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $clienteParaEditar['email']; ?>" required><br>

        <label>Data de Nascimento:</label>
        <input type="date" name="data_nascimento" value="<?php echo $clienteParaEditar['data_nascimento']; ?>" required><br>

        <input type="hidden" name="update" value="1">
        <input type="submit" value="Atualizar Cliente">
    </form>
<?php endif; ?>

<hr>

<!-- Listagem de clientes com opções de deletar ou editar -->
<?php
$sql = $pdo->prepare("SELECT * FROM clientes");
$sql->execute();
$fetchClientes = $sql->fetchAll();

foreach ($fetchClientes as $value) {
    echo '<a href="?delete='.$value["id"].'">(X)</a> ';
    echo $value["nome"]." | ".$value["email"]." | ".$value["data_nascimento"];
    echo ' <a href="?edit='.$value["id"].'">Editar</a>';
    echo '<hr>';
}
?>

