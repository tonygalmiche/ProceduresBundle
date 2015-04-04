


$(function () {
  var select_domaines=[];

  $('.date').datepicker({ dateFormat: 'dd/mm/yy' });



  $( "#select_domaines" ).on( "click", function() {
    val=$("#ove_proceduresbundle_procedures_domaineid").val();
    //alert(val);
    //$("#select_domaines2").val(val);
    tab=val.split(',');

    //alert(JSON.stringify(tab));

    $('#jstree').jstree('deselect_all');
    $('#jstree').jstree('select_node', tab);
    dialog.dialog( "open" );
  });

  dialog = $( "#dialog-form" ).dialog({
    autoOpen: false,
    height: 600,
    width: 600,
    modal: true,
    buttons: {
      "Annuler": function() {
        dialog.dialog( "close" );
      },
      "OK": function() {
        //val=$("#select_domaines2").val();
        //$("#ove_proceduresbundle_procedures_domaineid").val(val);
        //alert("Domaines selectionnés : "+$("#ove_proceduresbundle_procedures_domaineid").val()+" => "+$("#select_domaines2").val());

        //$("#ove_proceduresbundle_procedures_domaineid").select2("data", {id: "CA", text: "California"});
        //$("#ove_proceduresbundle_procedures_domaineid").select2("data", [{id: "CA", text: "California"},{id:"MA", text: "Massachusetts"}]); });

        $("#ove_proceduresbundle_procedures_domaineid").select2("data", select_domaines);
        //alert(JSON.stringify(select_domaines));

        //[{"id":"41","text":"5 - Gestion économique et financière"},{"id":"41","text":"5 - Gestion économique et financière"}]


        dialog.dialog( "close" );
      }
    },
    close: function() {
    }
  });


  //Doc : http://www.jstree.com/
  plugins= ["dnd", "search", "unique"];
  var jstree=$('#jstree').jstree({
    "plugins" : plugins,
    'core' : {
      'multiple' : true,
      'check_callback' : true,
      'data' : domaines_json
    }
  });



  //jstree.select_node("#41");


  $('#jstree').on('changed.jstree', function (e, data) {
    //console.log(data.selected);

    //console.log(jstree);
    //console.log(jstree.data().data.core.selected);
    //console.log(jstree.jquery);

    //$("#select_domaines2").val(data.selected);
    //console.log(data.node);
    //console.log(data.selected.length);
    //console.log(data.node.children.length);
    

//select_domaines2_json

    var i, j;
    //Boucle sur les sélections
    select_domaines=[];

    for(i = 0, j = data.selected.length; i < j; i++) {
      node=data.instance.get_node(data.selected[i]);
      //console.log(node);
      var obj = new Object();
      obj.id=node.id;
      obj.text=node.text;
      select_domaines.push(obj)
      //$("#node_id").val(node.id);
      //get_verificateur();
    }
    //$("#select_domaines2_json").val(JSON.stringify(select_domaines));
    //if(data.selected.length>0) {
    //  $("#node_detail").show(100);
    //} else {
    //  $("#node_detail").hide(100);
    //}
    
  });














  // Doc : http://handsontable.com/index.html


  x=$("#ove_proceduresbundle_procedures_intervenants").val();



  if(x=='') {
     data=[[''],['']];
  } else {
    data=JSON.parse(x);
  }
  //alert(data);
  //if(data=='')  data=[[''],['']];

  $("#handsontable_intervenants").handsontable({
    data: data,
    colHeaders: ['Intervenants', 'Responsabilités'],  // Titre des colonnes
    colWidths: [400,600],                             // Largeur des colonnes (number or a function)
    minRows: 2,                                       // Nombre de lignes mini
    minCols: 2,                                       // Nombre de colonnes mini
    minSpareRows: 1,                                  // Nombre de lignes à ajouter à la fin quand la dernière ligne est remplie
    manualColumnResize: true,                         // Possibilité de redimenssionner les colonnes
    autoWrapRow: true,                                // Permet de passer à la ligne suivante avec la touche TAB si la fin de ligne est atteinte
    contextMenu: {                                    // Menu contextuel : Seulement 2 entrées avec traduction en français
      items: {
        "row_above": {
          name: 'Insérer une ligne avant',
        },
        "remove_row": {
          name: 'Supprimer ces lignes',
        },
      }
    },
    afterChange: function (change, source) {
      intervenants_update();
    },
    afterRemoveRow: function (change, source) {
      intervenants_update();
    },
  });


  function intervenants_update() {
    var handsontable = $("#handsontable_intervenants").data('handsontable');
    var x=handsontable.getData();
    $("#ove_proceduresbundle_procedures_intervenants").val(JSON.stringify(x));
  }



  //$("#handsontable_intervenants").handsontable('selectCell', data.length-1, 0);
  $("#handsontable_intervenants").handsontable('selectCell', 0, 0);











  select_type();
  $("#ove_proceduresbundle_procedures_type").on("change", function(e) { 
    select_type();
    //alert($("#ove_proceduresbundle_procedures_type").val());
  });






  //** Select domaine *************************************
  $("#ove_proceduresbundle_procedures_domaineid").select2({
    multiple: true,
    maximumSelectionSize: 3,
    data: domaine,
  });
  //*******************************************************

  //** Select mot_cle *************************************
  $("#ove_proceduresbundle_procedures_mots_clesid").select2({
    multiple: true,
    maximumSelectionSize: 10,
    data: mots_cles,
  });
  //*******************************************************


  //** Select rédacteurs **********************************
  /*
  $("#ove_proceduresbundle_procedures_redacteursid,#ove_proceduresbundle_procedures_verificateursid,#ove_proceduresbundle_procedures_approbateursid,#ove_proceduresbundle_procedures_lecteursid").select2({
    multiple: true,
    maximumSelectionSize: 10,
    data: mots_cles,
  });
  */
  //*******************************************************



  //** Select rédacteurs et lecteurs **********************
  var param={
    multiple: true,
    maximumSelectionSize: 10,
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
  $("#ove_proceduresbundle_procedures_redacteursid,#ove_proceduresbundle_procedures_lecteursid").select2(param);
  $("#ove_proceduresbundle_procedures_redacteursid").select2("data", redacteurs);
  $("#ove_proceduresbundle_procedures_lecteursid").select2("data", lecteurs);
  //*******************************************************


  //** Groupes de rédacteurs et de lecteurs ***************
  param.multiple=false;
  param.minimumInputLength=0;
  param.ajax.url='../../ajax/ajout_groupe';
  $("#ajout_redacteurs,#ajout_lecteurs").select2(param);


  $("#ajout_redacteurs").on("change", function(e) { 
    idinput="#ove_proceduresbundle_procedures_redacteursid";
    if(e.added.id=="0") {
      creer_groupe(this,e.added.text,idinput);
    } else {
      get_groupe(this,e.added.id,idinput);
    }
  });

  $("#ajout_lecteurs").on("change", function(e) { 
    idinput="#ove_proceduresbundle_procedures_lecteursid";
    if(e.added.id=="0") {
      creer_groupe(this,e.added.text,idinput);
    } else {
      get_groupe(this,e.added.id,idinput);
    }
  });
  //*******************************************************


  //** Trame de la description ****************************
  param.ajax.url='../../ajax/select_trame';
  $("#trame").select2(param);
  $("#trame").on("change", function(e) { 
    get_trame(e.added.id);
  });
  //*******************************************************


});



function select_type() {
  v=$("#ove_proceduresbundle_procedures_type").val();
  if(v=="regle_gestion") {
    $("#diagramme").hide();
  } else {
    $("#diagramme").show();
  }
}



function creer_groupe(obj,groupe,idinput) {
  //alert("Ajouter "+groupe);
  membres=$(idinput).val();
  jQuery.ajax({
    type: 'GET', // Le type de ma requete
    dataType: "json",
    url: '../../ajax/creer_groupe',   // L'url vers laquelle la requete sera envoyee
    data: {
        groupe: groupe,    // Les donnees que l'on souhaite envoyer au serveur au format JSON
        membres: membres
    }, 
    success: function(data, textStatus, jqXHR) {
      //console.log(data);
      if(data.err!="") {
        alert(data.err);
      } else {
        $(obj).select2("val", "");
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        alert("Problème d'accès au serveur");
    }
  }); 
}


function get_groupe(obj,id,idinput) {
  //alert("Ajouter "+groupe);
  membres=$(idinput).val();
  jQuery.ajax({
    type: 'GET', // Le type de ma requete
    dataType: "json",
    url: '../../ajax/get_groupe',   // L'url vers laquelle la requete sera envoyee
    data: {
        id: id,    // Les donnees que l'on souhaite envoyer au serveur au format JSON
        membres: membres
    }, 
    success: function(data, textStatus, jqXHR) {
      //console.log(data);
      if(data.err!="") {
        alert(data.err);
      } else {
        $(idinput).select2("data", data.results);
        //$(obj).select2("val", "");
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        alert("Problème d'accès au serveur");
    }
  }); 
}


function get_trame(id) {
  jQuery.ajax({
    type: 'GET', // Le type de ma requete
    dataType: "json",
    url: '../../ajax/get_trame',   // L'url vers laquelle la requete sera envoyee
    data: {
        id: id    // Les donnees que l'on souhaite envoyer au serveur au format JSON
    }, 
    success: function(data, textStatus, jqXHR) {
      if(data.err!="") {
        alert(data.err);
      } else {
        //console.log(data);
        //console.log(CKEDITOR.instances.ove_proceduresbundle_procedures_description.getData());
        CKEDITOR.instances.ove_proceduresbundle_procedures_description.setData(data.contenu);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        alert("Problème d'accès au serveur");
    }
  }); 
}

/*
function delete_procedure() {
  return confirm ("Voulez-vous vraiment mettre à la corbeille cette procédure ?")
}
*/


/*
$(function () {

  //Permet de sélectionner automatiquement le texte de l'input lors de la prise de focus pour faciliter la modification
  $("#p_domaine,#p_fiche").focus(function(){
      this.select();
  });


  //** Champs à modifier lors de la perte de focus ******************
  t=['fiche', 'domaine', 'version', 'indice']; // Tableau des champs à modifier
  s="";
  $.each(t, function( index, value ){
    s=s+"#"+form_id+"_"+value+",";
  });
  s=s.substring(0,s.length-1);
  $(s).on('change', function () {
    champ=this.id.substring(form_id.length+1);
    set(champ,this.value);
  });
  //*****************************************************************


  //** Enregistrement des modifications du champ ckeditor ***********
  if (typeof CKEDITOR != 'undefined') {
    for (var i in CKEDITOR.instances) {
      CKEDITOR.instances[i].on('blur', function() { 
        champ=this.name;
        champ=champ.substring(32);
        value=CKEDITOR.instances[i].getData();
        value=this.getData();
        set(champ,value);
      });
    }
  }
  //*****************************************************************

});


function set(champ,value) {
  id=$("#p_id").val();
  jQuery.ajax({
    type: 'GET', 
    dataType: "json",
    url: 'ajax/set',   
    data: {
        id: id,
        champ: champ,
        value: value
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
*/


