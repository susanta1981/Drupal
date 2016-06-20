<!DOCTYPE html>
<html>
  <head>
    <script src="jquery.js"></script>
    <script src="flip.js"></script>
    <title>jQuery plugin</title>
    <style type="text/css">
      body {
        margin: 0 100px;
      }
      .card {
        width: 100px;
        height: 100px;
        margin: 20px;
        display: inline-block;
      }
      
      .front, .back {
        border: 2px gray solid;
        padding: 10px;
      }
      .front {
        background-color: #ccc;
      }
      .back {
        background-color: red;
      }
    </style>
  </head>
  <body>
    <?php for($i=1;$i<10; $i++) :?>
	<div id="card-<?php print $i;?>" class="card">
      <div class="front">
        <button id="flip-<?php print $i;?>" class="flip-btn">Flip</button>  Front
      </div>
      <div class="back">
        <button id="unflip-<?php print $i;?>" class="unflip-btn">UnFlip</button>  Back
      </div>
    </div>
	<?php endfor;?>

    <script type="text/javascript">
    $(function(){
      $(".card").flip({
        trigger: "manual"
      });
	  $(".flip-btn").click(function(){
        var id = $(this).attr('id').split('-');
		$("#card-"+id[1]).flip(true);
      });

      $(".unflip-btn").on('click', function(){
        var id = $(this).attr('id').split('-');
		$("#card-"+id[1]).flip(false);
      });
    });
    </script>
  </body>
</html>
