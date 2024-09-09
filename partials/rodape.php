<?php
    require_once('admin/class/ClassFrase.php');

    $frase = new ClassFrase();
    $fraseAtualizada =  $frase->obterFraseAleatoria();


?>
<footer class="rodape"> 
      <div class="site">
        <div>
        </div>
        <div>
          <div>
            <h3>Menu</h3>
            <img src="assets/img/setabaixo.png" alt="seta para baixo">
          </div>
          <div>
            <h3>Frase do dia</h3>
            <img src="assets/img/setabaixo.png" alt="seta para baixo">
          </div>
          <div>
            <h3>Contato</h3>
            <img src="assets/img/setabaixo.png" alt="seta para baixo">
          </div>
        </div>
        <div>
          <ul>
            <li><a href="index.php">Início</a></li>
            <li><a href="sobre.php">Sobre</a></li>
            <li><a href="aluno.php">Aluno</a></li>
            <li><a href="agendamento.php">Agendamento</a></li>
          </ul>
          <ul>
            <h6>"<?php echo $fraseAtualizada; ?>"</h6>
          </ul>
          <ul>
            <li><a href="https://wa.me/5511999180518?text=Ol%C3%A1%2C%20gostaria%20de%20saber%20mais%20informa%C3%A7%C3%B5es."><img src="assets/img/whatsapp.png" alt="whatsapp teacher camila"></a></li>
            <li><a href="#"><img src="assets/img/envelope.png" alt="whatsapp teacher camila"></a></li>
            <li><a href="#"><img src="assets/img/facebook.png" alt="whatsapp teacher camila"></a></li>
            <li><a href="#"><img src="assets/img/instagram.png" alt="whatsapp teacher camila"></a></li>
          </ul>
        </div>
      </div>
      <div>
        <h4> &reg; Todos os direitos reservados - Camila</h4>
      </div>
    </footer>