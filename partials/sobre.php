<?php
require_once('admin/class/ClassAluno.php');

$aluno = new ClassAluno();
$totalAlunos = contarAlunos();

require_once('admin/class/ClassAtividade.php');

$atividade = new ClassAtividade();
$totalAtividade = contarAtividade();

//print_r($lista);
?>
<article class="sobre wow animate__animated animate__fadeInUp">
  <div class="site">
    <h2>Sobre mim</h2>
    <div>
      <div class="ftexto">
        <img src="assets/img/camila.svg" alt="Foto da professora">
        <p>Comecei a estudar inglês aos 6 anos e fiz cursos até os 17. Desde então, dou aulas de inglês há 24 anos, começando em 1999. Aprendi o idioma no Brasil, cursei Letras de 2009 a 2013 e fiz meu intercâmbio em Londres aos 25 anos, embora já soubesse falar inglês antes de ir.</p>
      </div>
    </div>
    <div class="dados">
      <div>
        <h4><span class="counter-up" data-count-to="<?php echo $totalAlunos; ?>"></span> </h4>
        <p>Alunos Matriculados</p>
      </div>
      <div>
        <h4><span class="counter-up" data-count-to="<?php echo $totalAtividade; ?>"></span> </h4>
        <p>Atividades Completas</p>
      </div>
      <div>
        <h4><span class="counter-up" data-count-to="100"></span>%</h4>
        <p>Satisfação dos alunos</p>
      </div>
    </div>
  </div>
</article>