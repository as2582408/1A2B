<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <meta charset="utf-8">
    <form name='form' id='form'>
        <input type='text' name='name' id='poipoi' />
        <input type='button' name='submit' id='button' value="oao" /> 
    </form>
    <p id="p1"></p>

    <script type="text/javascript">
    $(document).ready(function(){
        $("#button").click(function() {
            var data = document.getElementById("poipoi").value;
        $.ajax({
            url: "/123.php",   //後端的URL
            type: "POST",   //用POST的方式
            dataType: "text",   //response的資料格式
            cache: false,   //是否暫存
            data: {
                oao:data
            },
            success: function(response) {
                console.log(response);  //成功後回傳的資料
                document.getElementById("p1").innerHTML = response;
            },
            error: function(){ 
                console.log('2334');  //成功後回傳的資料
        	    } 
            });
        });
    });
</script>
