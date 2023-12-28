<div id="editor">@isset($env){{$env}}@endisset</div>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/dot");
</script>






<style type="text/css" media="screen">
    #editor { 
        height: 550px;
        width: 100%;
    }
</style>