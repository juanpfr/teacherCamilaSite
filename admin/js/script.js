function LoginAdmin(){
  //document.getElementById() pode ser substituído por $("")
  $("#formLoginAdmin").click(function(){

      //alert('Cheguei aqui.')

      var formdata = $("#formLoginAdmin").serialize(); //serialize pega todos os dados do input

      //console.log(formdata);

      $.ajax({

          //cabeçalho do conteúdo poggers
          url: "./class/ClassUsuario.php",
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
                  var idUsuario = data.idUsuario;
                  window.location.href = 'http://localhost/teacher_camila/admin/index.php?p=dashboard'
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