// WOW 

new WOW().init();

//Menu abrir legal

function menuShow() {
  let menuMobile = document.querySelector('.menuMobile');
  menuMobile.classList.toggle('open');
}

// SCROLL REVEAL

// Importe a biblioteca scrollReveal (verifique como instalá-la)
const sr = ScrollReveal({
    reset: true, // Reinicia a animação ao sair e entrar na tela
    // Outros atributos personalizáveis
    
});

// Selecione os elementos que deseja animar
const elementsToAnimate = document.querySelectorAll(".counter-up");

// Aplique a animação aos elementos
sr.reveal(elementsToAnimate);

const tempo_intervalo = 10; //ms -> define a velocidade da animação
const tempo = 3000; //ms -> define o tempo total da animaçao

$('.counter-up').each(function() {  
  let count_to = parseInt($(this).data('countTo'));
  let intervalos = tempo / tempo_intervalo; //quantos passos de animação tem
  let incremento = count_to / intervalos; //quanto cada contador deve aumentar
  let valor = 0;
  let el = $(this);
  
  let timer = setInterval(function() {
    if (valor >= count_to){ //se já contou tudo tem de parar o timer
      valor = count_to;
      clearInterval(timer);
    }
    
    let texto = valor.toFixed(0).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    el.text(texto);
    valor += incremento;      
  }, tempo_intervalo);
});

// Jogos

$('.catalogo').slick({
  centerMode: true,
  centerPadding: '60px',
  slidesToShow: 3,
  responsive: [{
  breakpoint: 768,
    settings: {
      arrows: false,
      centerMode: true,
      centerPadding: '40px',
      slidesToShow: 3
    }
  },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1
      }
    }
  ]
});

// ############################################# Login #############################################

function Login(){
  //document.getElementById() pode ser substituído por $("")
  $("#formLogin").click(function(){

      //alert('Cheguei aqui.')

      var formdata = $("#formLogin").serialize(); //serialize pega todos os dados do input

      //console.log(formdata);

      $.ajax({

          //cabeçalho do conteúdo poggers
          url: "./admin/class/ClassAluno.php",
          method: "POST",
          data: formdata,
          dataType: "json",

          //se der certo
          success: function(data){
              //bem sucedido (ai sim hein kk)
              if(data.success){
                  $("#msglogin").html(
                      "<div class='msgLogin'>" + data.success + "</div>"
                  )
                  var idAluno = data.idAluno;
                  window.location.href = 'http://localhost/teacherCamilaSite/index'
              }else{
                  //Não deu certo (login desativado ou bloqueado sla alguma coisa assim)
                  $("#msglogin").html(
                      "<div class='msgInvalido'>" + data.success + "</div>"
                  )
              }
          },

          //se der errado (vish)
          error: function(xhr, status, error){
              console.log(error);
          }
      })
  })
}

// Pós login (menuzinho brabo)

const $menuLogin = document.getElementById('menuLogin');
const $menuTrigger = document.getElementById('menuTrigger');
let state = $menuLogin.dataset.aberto;

$menuTrigger.addEventListener('click', () => {
  // Alternar o estado do menu entre 'true' e 'false'
  state = (state === "false") ? "true" : "false";
  
  // Atualizar o atributo data-aberto com o novo estado
  $menuLogin.dataset.aberto = state;

  // Controlar a visibilidade do submenu com base no estado
  const $subMenuLogin = $menuLogin.querySelector('.subMenuLogin');
  if(state === "true"){
    $subMenuLogin.style.display = 'block';
  } else {
    $subMenuLogin.style.display = 'none';
  }
});