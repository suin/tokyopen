<p><{"Finish Unzip"|t}></p>
<script>
<{*
  $(document).ready(function(){
    $("#install_progress").load("index.php?controller=install&action=unzip&target_key=<{$target_key}> #message");
  });
*}>

$(document).ready(function(){
    $.get("index.php", 
        {
            controller:"install",
            action:"copy",
            target_key:"<{$target_key}>",
            target_type:"<{$target_type}>",
            
            ajaxmode: 1
        },
        function(data){
            $("#install_progress").append(data);
        }
        );
});

</script>
