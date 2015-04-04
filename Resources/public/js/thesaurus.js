$(function () {


  //** Select vérificateurs **********************
  var param={
    multiple: false,
    minimumInputLength: 2,
    ajax: { 
      url: '../../ajax/select_ldap', // L'url vers laquelle la requete sera envoyee
      dataType: 'json',
      quietMillis: 50,
      data: function (term, page) {
        return {
          q: term,
        };
      },
      results: function (data, page) {
        return {
          results: data.results, 
          more: data.more
        };
      },
    }
  };
  $("#node_verificateur").select2(param);
  //*******************************************************





  plugins= ["dnd", "search", "unique"];

  //Doc : http://www.jstree.com/
  $('#jstree').jstree({
    "plugins" : plugins,
    'core' : {
      'multiple' : false,
      'check_callback' : true,
      'data' : json
    }
  });


  $('#jstree').on('changed.jstree', function (e, data) {
    //console.log(data.node);
    //console.log(data.node.children.length);
    var i, j;
    //Boucle dans le cas ou il y aurait plusieurs sélections ce qui normalement est bloqué
    for(i = 0, j = data.selected.length; i < j; i++) {
      node=data.instance.get_node(data.selected[i]);
      $("#node_id").val(node.id);
      get_verificateur();
    }

    if(data.selected.length>0) {
      $("#node_detail").show(100);
    } else {
      $("#node_detail").hide(100);
    }

  });




  $('#developper').on('click', function () {
    $('#jstree').jstree('open_all');
  });

  $('#reduire').on('click', function () {
    $('#jstree').jstree('close_all');
  });


  //Recherche
  var to = false;
  $('#recherche').keyup(function () {
    if(to) { clearTimeout(to); }
    to = setTimeout(function () {
      var v = $('#recherche').val();
      $('#jstree').jstree(true).search(v);
    }, 250);
  });


  $('#node_verificateur').on('change', function () {
    set_verificateur();
  });

  $("#node_verificateur").focus(function(){
      this.select();
  });



});








function set_verificateur() {
  node_id=$("#node_id").val();
  verificateur=$("#node_verificateur").val();

  console.log(verificateur);


  jQuery.ajax({
    type: 'GET', // Le type de ma requete
    dataType: "json",
    url: '../../ajax/set_verificateur',   // L'url vers laquelle la requete sera envoyee
    data: {
        id: node_id,
        verificateur: verificateur,    // Les donnees que l'on souhaite envoyer au serveur au format JSON
    }, 
    success: function(data, textStatus, jqXHR) {
      if(data.err!="") {
        alert(data.err);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        alert("Problème d'accès au serveur");
    }
  }); 
}


function get_verificateur() {
  node_id=$("#node_id").val();
  jQuery.ajax({
    type: 'GET', // Le type de ma requete
    dataType: "json",
    url: '../../ajax/get_verificateur',   // L'url vers laquelle la requete sera envoyee
    data: {
        id: node_id
    }, 
    success: function(data, textStatus, jqXHR) {
      if(data.err!="") {
        alert(data.err);
      } else {
        $("#node_verificateur").select2("data", data.json);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        alert("Problème d'accès au serveur");
    }
  }); 
}









