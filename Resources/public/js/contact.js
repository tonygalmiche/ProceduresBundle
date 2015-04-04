


$(function () {
  var select_domaines=[];


  //** Select domaine *************************************
  $("#domaines").select2({
    multiple: true,
    maximumSelectionSize: 3,
    data: domaine,
  });
  //*******************************************************




  $( "#select_domaines" ).on( "click", function() {
    val=$("#domaines").val();
    //alert(val);

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
        $("#domaines").select2("data", select_domaines);
        //$("#destinataires").val(select_domaines);
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


  $('#jstree').on('changed.jstree', function (e, data) {
    var i, j;
    select_domaines=[];
    for(i = 0, j = data.selected.length; i < j; i++) {
      node=data.instance.get_node(data.selected[i]);
      var obj = new Object();
      obj.id=node.id;
      obj.text=node.text;
      select_domaines.push(obj)

      console.log(select_domaines);

    }
  });






});

