<?php

if (isset($_POST['nomeAluno'])) {
    $nomeAluno = $_POST['nomeAluno'];
    $telefoneAluno = $_POST['telefoneAluno'];
    $emailAluno = $_POST['emailAluno'];
    $senhaAluno = $_POST['senhaAluno'];

    $statusAluno = 'ATIVO';
    $altAluno = 'foto' . $nomeAluno;

    if (!empty($_FILES['fotoAluno']['name'])) {
        $arquivo = $_FILES['fotoAluno'];
        if ($arquivo['error']) {
            throw new Exception('O erro foi: ' . $arquivo['error']);
        }

        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $nomeCliFoto = str_replace(' ', '', $nomeAluno);
        $nomeCliFoto = iconv('UTF-8', 'ASCII//TRANSLIT', $nomeCliFoto);
        $nomeCliFoto = strtolower($nomeCliFoto);

        $novoNome = $aluno->idAluno . '_' . $nomeCliFoto . '.' . $extensao;

        if (move_uploaded_file($arquivo['tmp_name'], 'admin/img/aluno/' . $novoNome)) {
            $fotoAluno = 'aluno/' . $novoNome;
        } else {
            throw new Exception('Não foi possível realizar o upload!');
        }
    } else {
        $fotoAluno = $aluno->fotoAluno;
    }

    $aluno->nomeAluno = $nomeAluno;
    $aluno->telefoneAluno = $telefoneAluno;
    $aluno->emailAluno = $emailAluno;
    $aluno->senhaAluno = $senhaAluno;
    $aluno->fotoAluno = $fotoAluno;
    $aluno->altAluno = $altAluno;
    $aluno->statusAluno = $statusAluno;

    $aluno->Atualizar();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>

    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!--ícone-->
    <link rel="icon" type="image/ico" href="assets/img/logo.ico" />

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <section>
        <div>
            <div>
                <h2 id="h2MeuPerfil">Meu Perfil</h2>
            </div>
            <div>
                <div class="container mt-5">
                    <form action="index.php?p=aluno&al=atualizar&id=<?php echo $aluno->idAluno; ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-4 formAluno">
                                <img src="admin/img/<?php echo $aluno->fotoAluno; ?>" class="img-fluid" alt="<?php echo $aluno->nomeAluno; ?>" id="imgFoto">
                                <input type="file" class="form-control" id="fotoAluno" name="fotoAluno" style="display: none;">
                                <div class="mb-3">
                                    <br>
                                    <label style="font-size: 1.5em;" for="fotoAluno" class="form-label">Clique na foto para atualizar</label>
                                </div>
                            </div>
                            <div class="col-8">

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-9">
                                            <label for="nomeAluno" class="form-label">Nome</label>
                                            <input type="text" class="form-control" id="nomeAluno" name="nomeAluno" disabled value="<?php echo $aluno->nomeAluno; ?>">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-4 tel">
                                        <div class="mb-3">
                                            <label for="telefoneAluno" class="form-label">Telefone</label>
                                            <input type="text" class="form-control" id="telefoneAluno" name="telefoneAluno" required value="<?php echo $aluno->telefoneAluno; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="emailAluno" class="form-label">E-mail</label>
                                            <input type="email" class="form-control" id="emailAluno" name="emailAluno" disabled value="<?php echo $aluno->emailAluno; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="senhaAluno" class="form-label">Senha</label>
                                            <input type="password" class="form-control" id="senhaAluno" name="senhaAluno" required value="<?php echo $aluno->senhaAluno; ?>">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        // mascara telefone celular

        var telefoneAluno = document.getElementById("telefoneAluno");

        telefoneAluno.addEventListener("input", () => {

            //Remover caracteres não numéricos usando expressão regular /\D/g e limitar a 11 digitos.
            var limparValor = telefoneAluno.value.replace(/\D/g, "").substring(0, 11);

            //dividir a string em um array de caracteres individuais
            var numerosArray = limparValor.split("");

            //criar variavel para receber o número formatado
            var numeroFormatado = "";

            //acessa o if quando a quantidade de numeros é maior do que zero
            if (numerosArray.length > 0) {
                numeroFormatado += `(${numerosArray.slice(0,2).join("")})`;
            }

            //acessa o if quando a quantidade de numeros é maior do que dois
            if (numerosArray.length > 2) {
                numeroFormatado += ` ${numerosArray.slice(2,7).join("")}`;
            }

            //acessa o if quando a quantidade de numeros é maior do que sete
            if (numerosArray.length > 7) {
                numeroFormatado += `-${numerosArray.slice(7,11).join("")}`;
            }

            //enviar para o campo formatado
            telefoneAluno.value = numeroFormatado;

        })

        document.getElementById('imgFoto').addEventListener('click', function() {
            document.getElementById('fotoAluno').click();
        });

        document.getElementById('fotoAluno').addEventListener('change', function(event) {
            let imgFoto = document.getElementById('imgFoto');
            let arquivo = event.target.files[0];
            if (arquivo) {
                let carregar = new FileReader();
                carregar.onload = function(e) {
                    imgFoto.src = e.target.result;
                }
                carregar.readAsDataURL(arquivo);
            }
        });
    </script>

    <!-- Outros scripts -->
    <script src="assets/js/wow.min.js"></script>
    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
    <script src="assets/js/slick.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>