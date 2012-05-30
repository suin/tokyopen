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
            action:"download",
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
<h1><{$title}></h1>
<div id="install_progress">
<p><{"Downloading ..."|t}></p>
</div>