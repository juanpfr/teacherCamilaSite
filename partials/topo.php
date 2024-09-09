<div class="faixa">
    <div>
        <div>
            <a href="#">
                <h5>Agendamento rápido</h5>
                <p>Economize tempo agendando sua aula</p>
            </a>
        </div>
        <div>
            <a href="#">
                <h5>Melhore seu inglês</h5>
                <p>Inglês para trabalho ou desenvolvimento pessoal</p>
            </a>
        </div>
    </div>
</div>
<!--Faixa Menu-->
<div class="faixaMenu">
    <div class="site">

        <!--Logo-->
        <div style="align-items: center;">
            <h1>Teacher Camila</h1>
        </div>

        <!--Menu para desktop-->
        <nav class="menu">
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="index.php?pa=sobre">Sobre</a></li>
                <li><a href="index.php?pa=aluno">Aluno</a></li>
                <li><a href="index.php?pa=agendamento">Agendamento</a></li>
                <li><a href="index.php?pa=contato">Contato</a></li>
            </ul>
        </nav>

        <!-- Sessão de login -->
        <div>
            <?php if ($tipo == 'aluno') { ?>
                <style>
                    .subtitulo {
                        padding: 55px 0 !important;
                    }

                    .faixaMenu {
                        padding: 35px 0 !important;
                    }
                </style>
                <div id="loginFeito" style="display:flex; align-items:center; gap: 14px;">
                    <img src="admin/img/<?php echo htmlspecialchars($fotoAluno); ?>" alt="<?php echo $altAluno; ?>" style="width: 65px; height: 65px; border-radius: 50%;">
                    <div id="menuLogin" class="menuLogin" data-aberto="false">
                        <span id="menuTrigger" class="menuTrigger">
                            <i class="bi bi-caret-down-square"></i>
                            <i class="bi bi-caret-up-square"></i>
                        </span>
                        <ul class="subMenuLogin">
                            <li class="liLogin"><a href="index.php?pa=meuPerfil">Meu Perfil</a></li>
                            <li class="liLogin"><a href="index.php?pa=meuPerfil">Aulas</a></li>
                            <li class="liLogin"><a href="https://teachercamila.smpsistema.com.br/admin/destroy_session.php">Sair</a></li>
                        </ul>
                    </div>
                </div>
            <?php } else { ?>
                <a href="login.php">Login</a>
            <?php } ?>
        </div>

        <!-- Ícone do menu mobile -->
        <div class="menuMobileIcon">
            <button onclick="menuShow()"><img src="assets/img/menu-hamburguer.png" alt="Menu"></button>
        </div>

    </div>

    <!-- Menu Mobile -->
    <div class="menuMobile">
        <!--Logo-->
        <div style="align-items: center;">
            <h1>Teacher Camila</h1>
        </div>

        <div>
            <?php if ($tipo == 'aluno') { ?>
                <style>
                    .subtitulo {
                        padding: 55px 0 !important;
                    }

                    .faixaMenu {
                        padding: 35px 20px !important;
                    }
                </style>
                <div id="loginFeito" style="display:flex; align-items:center; gap: 14px;">
                    <img src="admin/img/<?php echo htmlspecialchars($fotoAluno); ?>" alt="<?php echo $altAluno; ?>" style="width: 65px; height: 65px; border-radius: 50%;">
                    <div id="menuLogin" class="menuLogin" data-aberto="false">
                        <span id="menuTrigger" class="menuTrigger">
                            <i class="bi bi-caret-down-square"></i>
                            <i class="bi bi-caret-up-square"></i>
                        </span>
                        <ul class="subMenuLogin">
                            <li class="liLogin"><a href="index.php?pa=meuPerfil">Meu Perfil</a></li>
                            <li class="liLogin"><a href="index.php?pa=meuPerfil">Aulas</a></li>
                            <li class="liLogin"><a href="https://teachercamila.smpsistema.com.br/admin/destroy_session.php">Sair</a></li>
                        </ul>
                    </div>
                </div>
            <?php } else { ?>
                <a href="login.php">Login</a>
            <?php } ?>
        </div>

        <!--Menu-->
        <nav class="menu">
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="index.php?pa=sobre">Sobre</a></li>
                <li><a href="index.php?pa=aluno">Aluno</a></li>
                <li><a href="index.php?pa=agendamento">Agendamento</a></li>
                <li><a href="index.php?pa=contato">Contato</a></li>
            </ul>
        </nav>
    </div>
</div>